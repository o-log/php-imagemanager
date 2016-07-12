<?php


namespace OLOG\ImageManager;


trait ImageManagerPresetTrait
{
    protected $alias;
    
    public function __construct($preset_alias)
    {
        $this->setAlias($preset_alias);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }
}