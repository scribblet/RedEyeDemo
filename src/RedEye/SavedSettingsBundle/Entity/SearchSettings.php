<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ross
 * Date: 25/02/14
 * Time: 8:34 PM
 */

namespace RedEye\SavedSettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="saved_searches")
 */

class SearchSettings {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $searchCriteria;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $searchText;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $searchDesc;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set searchCriteria
     *
     * @param string $searchCriteria
     * @return SearchSettings
     */
    public function setSearchCriteria($searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
    
        return $this;
    }

    /**
     * Get searchCriteria
     *
     * @return string 
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Set searchText
     *
     * @param string $searchText
     * @return SearchSettings
     */
    public function setSearchText($searchText)
    {
        $this->searchText = $searchText;
    
        return $this;
    }

    /**
     * Get searchText
     *
     * @return string 
     */
    public function getSearchText()
    {
        return $this->searchText;
    }

    /**
     * Set searchDesc
     *
     * @param string $searchDesc
     * @return SearchSettings
     */
    public function setSearchDesc($searchDesc)
    {
        $this->searchDesc = $searchDesc;
    
        return $this;
    }

    /**
     * Get searchDesc
     *
     * @return string 
     */
    public function getSearchDesc()
    {
        return $this->searchDesc;
    }
}