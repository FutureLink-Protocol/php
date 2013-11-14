<?php
namespace FLP;

use Phraser;

class Security
{
    public $debug = false;
    public $itemsAdded = false;
    public $verifications = array();
    public $verificationsCount = 0;
    public $metadata;

    function verify(Contents &$contents, $item)
    {
        if ($this->debug == true) {
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
        }

        foreach ($item->feed->entry as $i => $newEntry) {
            $verification = new Verification();

            //lets remove the new entry if it has already been accepted in the past
            foreach ($contents->entry as &$existingEntry) {
                if (
                    $existingEntry->pastlink->text == $newEntry->pastlink->text &&
                    $existingEntry->pastlink->href == $newEntry->pastlink->href
                ) {
                    $verification->reason[] = 'exists';
                    unset($item->feed->entry[$i]);
                }
            }

            // This query will *ALWAYS* fail if the destination page had been created/edited *PRIOR* to applying the 'Simple Wiki Attributes' profile!
            // Just recreate the destination page after having applied the profile in order to load it with the proper attributes.
            // TODO: consider adding a test on query failure in order to determine whether:
            //       1) the phrase isn't found, or
            //       2) the Simple Wiki Attributes profile wasn't in place at page-creation
            // ...then display a more meaningful error message
            Events::triggerLookupRevision(new Phraser\Phrase($newEntry->futurelink->text), $revision = new Revision());

            $verification->hashBy = Phraser\Parser::superSanitize(
                $newEntry->futurelink->author .
                $newEntry->futurelink->authorInstitution .
                $newEntry->futurelink->authorProfession
            );

            if ($revision->version == null) {
                unset($item->feed->entry[$i]);
            }

            $verification->foundRevision = $revision;
            $verification->metadataHere = $this->metadata->raw;
            $verification->textThere = new Phraser\Phrase($newEntry->futurelink->text);
            $verification->hashHere = hash_hmac("md5", $verification->hashBy, $verification->textThere);
            $verification->hashThere = $newEntry->futurelink->hash;
            $verification->exists = Phraser\Parser::hasPhrase(
                $revision->data,
                $verification->textThere
            );

            if ($verification->hashHere != $verification->hashThere) {
                $verification->reason[] = 'hash_tampering';
                unset($item->feed->entry[$i]);
            }
            /*
             * This does need added
                        if ($newEntry->futurelink->websiteTitle != $prefs['browsertitle']) {
                            $this->verifications[$i]['reason'][] = 'title';
                            unset($item->feed->entry[$i]);
                        }
            */
            if ($verification->exists == false) {
                if (empty($verification->reason)) {
                    $verification->reason[] = 'no_existence_hash_pass';
                } else {
                    $verification->reason[] = 'no_existence';
                }

                unset($item->feed->entry[$i]);
            }

            foreach ($newEntry->futurelink as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                    unset($item->feed->entry[$i]);
                }
            }

            foreach ($newEntry->pastlink as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                    unset($item->feed->entry[$i]);
                }
            }

            $this->verifications[] = $verification;
            $this->verificationsCount++;
        }

        if (empty($item->feed->entry) == false) {
            $this->itemsAdded = true;

            foreach ($item->feed->entry as &$entry) {
                Events::triggerAccepted('futureLink', $entry->futurelink->text);
            }

            if (empty($contents->entry) == true) {
                $contents->entry = array();
            }

            $contents->entry = array_merge($contents->entry, $item->feed->entry);
        }
    }
} 