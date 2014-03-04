<?php
namespace FLP;

/**
 * Class Pair
 * @package FLP
 */
class Pair extends Feeder
{
	/**
	 * @var Metadata
	 */
	public $future;

	/**
	 * @var Metadata
	 */
	public $past;

    private $futureRaw;
    private $pastRaw;

    /**
     * @param $past
     * @param $future
     */
    public function __construct(&$past, &$future)
    {
        $this->futureRaw = $future;
        $this->pastRaw = $past;

        $this->future =& MetadataAssembler::fromRawToMetaData($future);
        $this->past =& MetadataAssembler::fromRawToMetaData($past);
    }

    /**
     * @return string
     */
    public function raw()
    {
        return $this->pastRaw . $this->futureRaw;
    }

	public function revision()
	{
		return Data::getRevision($this->past->text);
	}
}