<?php
namespace FLP;

use Phraser;

/**
 * Class Security
 * @package FLP
 */
class Security
{
    public $debug = false;
    public $added = false;
    public $verifications = array();
    public $verificationsCount = 0;
    public $metadata;

    /**
     * @param Pair $pair
     * @param Revision $revision
     */
    function verify(Pair $pair, Revision $revision)
    {
        if ($this->debug == true) {
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
        }

	    $verification = new Verification();

	    $this->verifications[] =& $verification;
	    $this->verificationsCount++;

	    if (Data::getPair($pair)) {
            $verification->reason[] = 'exists';
	    } else {
		    Data::createPair($revision->title, $pair);

            $verification->hashBy = Phraser\Parser::superSanitize(
	            $pair->past->author .
	            $pair->past->authorInstitution .
	            $pair->past->authorProfession
            );

            $verification->foundRevision = $revision;
            $verification->metadataHere = $pair->past;
            $verification->textThere = (new Phraser\Phrase($pair->past->text))->sanitized;
            $verification->hashHere = hash_hmac("md5", $verification->hashBy, $verification->textThere);
            $verification->hashThere = $pair->past->hash;
            $verification->exists = Phraser\Parser::hasPhrase(
                $revision->data,
                $verification->textThere
            );

            if ($verification->hashHere != $verification->hashThere) {
                $verification->reason[] = 'hash_tampering';
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
            }

            foreach ($pair->future as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                }
            }

            foreach ($pair->past as $key => $value) {
                if (isset(MetadataAssembler::$acceptableKeys[$key]) && MetadataAssembler::$acceptableKeys[$key] == true) {
                    //all clear
                } else {
                    $verification->reason[] = 'metadata_tampering' . ($this->debug == true ? $key : '');
                }
            }

            $this->verifications[] = $verification;
            $this->verificationsCount++;
        }

        if (empty($verification->reason)) {
            $this->added = true;
            Events::triggerAccepted($pair);
        }
    }
} 