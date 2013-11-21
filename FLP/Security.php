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

        foreach ($item->feed->items as $i => $newItem) {
            $verification = new Verification();

            //lets remove the new items if it has already been accepted in the past
            foreach ($contents->items as &$existingItem) {
                if (
                    $existingItem->pastlink->text == $newItem->pastlink->text &&
                    $existingItem->pastlink->href == $newItem->pastlink->href
                ) {
                    $verification->reason[] = 'exists';
                    unset($item->feed->items[$i]);
                }
            }

            // This query will *ALWAYS* fail if the destination page had been created/edited *PRIOR* to applying the 'Simple Wiki Attributes' profile!
            // Just recreate the destination page after having applied the profile in order to load it with the proper attributes.
            // TODO: consider adding a test on query failure in order to determine whether:
            //       1) the phrase isn't found, or
            //       2) the Simple Wiki Attributes profile wasn't in place at page-creation
            // ...then display a more meaningful error message
            Events::triggerRevisionLookup(new Phraser\Phrase($newItem->futurelink->text), $revision = new Revision());

            $verification->hashBy = Phraser\Parser::superSanitize(
	            $newItem->futurelink->author .
	            $newItem->futurelink->authorInstitution .
	            $newItem->futurelink->authorProfession
            );

            if ($revision->version == null) {
                unset($item->feed->items[$i]);
            }

            $verification->foundRevision = $revision;
            $verification->metadataHere = $this->metadata->raw;
            $verification->textThere = new Phraser\Phrase($newItem->futurelink->text);
            $verification->hashHere = hash_hmac("md5", $verification->hashBy, $verification->textThere);
            $verification->hashThere = $newItem->futurelink->hash;
            $verification->exists = Phraser\Parser::hasPhrase(
                $revision->data,
                $verification->textThere
            );

            if ($verification->hashHere != $verification->hashThere) {
                $verification->reason[] = 'hash_tampering';
                unset($item->feed->items[$i]);
            }
            /*
             * This does need added
                        if ($newItem->futurelink->websiteTitle != $prefs['browsertitle']) {
                            $this->verifications[$i]['reason'][] = 'title';
                            unset($item->feed->items[$i]);
                        }
            */
            if ($verification->exists == false) {
                if (empty($verification->reason)) {
                    $verification->reason[] = 'no_existence_hash_pass';
                } else {
                    $verification->reason[] = 'no_existence';
                }

                unset($item->feed->items[$i]);
            }

            foreach ($newItem->futurelink as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                    unset($item->feed->items[$i]);
                }
            }

            foreach ($newItem->pastlink as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                    unset($item->feed->items[$i]);
                }
            }

            $this->verifications[] = $verification;
            $this->verificationsCount++;
        }

        if (empty($item->feed->items) == false) {
            $this->itemsAdded = true;

            foreach ($item->feed->items as &$items) {
                Events::triggerAccepted('futureLink', $items->futurelink->text);
            }

            if (empty($contents->items) == true) {
                $contents->items = array();
            }

            $contents->items = array_merge($contents->items, $item->feed->items);
        }
    }
} 