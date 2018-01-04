<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 * @UniqueEntity("email", ignoreNull=true)
 * @UniqueEntity("zohoId", ignoreNull=true)
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20, nullable=true)
     */
    private $mobile;
    

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2, nullable=true)
     */
    private $language;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=false)
     */
    private $newsletter = false;
    
    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;
    
    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="postalcode", type="string", length=10, nullable=true)
     */
    private $postalcode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="countrycode", type="string", length=2, nullable=true)
     */
    private $countrycode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private $message;
    
    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="contacts")
     */
    private $company;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="contacts", cascade={"persist"})
     */
    private $categories;
    
    /**
     * @ORM\ManyToOne(targetEntity="Source")
     */
    private $source;
    
    /**
     * @var string
     *
     * @ORM\Column(name="zoho_id", type="string", length=50, nullable=true, unique=true)
     */
    private $zohoId;
    
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function __construct()
    {
    	$this->categories = new ArrayCollection();
    }
    
    public function __toString()
    {
    	$string = $this->getFirstname() . ' ' . $this->getLastname();
    	if($this->getEmail() != '') {
    		$string .= '( ' . $this->getEmail() . ')';
    	}
    	return $string;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Contact
     */
    public function setPhone($phone)
    {
    	$this->phone = $phone;
    
    	return $this;
    }
    
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
    	return $this->phone;
    }
    
    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Contact
     */
    public function setMobile($mobile)
    {
    	$this->mobile = $mobile;
    
    	return $this;
    }
    
    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
    	return $this->mobile;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Contact
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Contact
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return Contact
     */
    public function setNewsletter($newsletter)
    {
    	$this->newsletter = $newsletter;
    
    	return $this;
    }
    
    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
    	return $this->newsletter;
    }
    
    /**
     * Set address
     *
     * @param string $address
     *
     * @return Contact
     */
    public function setAddress($address)
    {
    	$this->address = $address;
    
    	return $this;
    }
    
    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
    	return $this->address;
    }
    
    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Contact
     */
    public function setAddress2($address)
    {
    	$this->address2 = $address;
    
    	return $this;
    }
    
    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
    	return $this->address2;
    }
    
    /**
     * Set postalcode
     *
     * @param string $postalcode
     *
     * @return Contact
     */
    public function setPostalcode($postalcode)
    {
    	$this->postalcode = $postalcode;
    
    	return $this;
    }
    
    /**
     * Get postalcode
     *
     * @return string
     */
    public function getPostalcode()
    {
    	return $this->postalcode;
    }
    
    /**
     * Set city
     *
     * @param string $city
     *
     * @return Contact
     */
    public function setCity($city)
    {
    	$this->city = $city;
    
    	return $this;
    }
    
    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
    	return $this->city;
    }
    
    /**
     * Set countrycode
     *
     * @param string $countrycode
     *
     * @return Contact
     */
    public function setCountrycode($countrycode)
    {
    	$this->countrycode = $countrycode;
    
    	return $this;
    }
    
    /**
     * Get countrycode
     *
     * @return string
     */
    public function getCountrycode()
    {
    	return $this->countrycode;
    }
    
    /**
     * Set description
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
    	$this->message = $message;
    
    	return $this;
    }
    
    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
    	return $this->message;
    }

    
    /**
     * 
     * @param Company $company
     * @return Contact
     */
    public function setCompany($company)
    {
    	$this->company = $company;
    	
    	return $this;
    }
    
    /**
     * 
     * @return Company
     */
    public function getCompany()
    {
    	return $this->company;
    }
    
    /**
     *
     * @param Source $source
     * @return Contact
     */
    public function setSource($source)
    {
    	$this->source = $source;
    	 
    	return $this;
    }
    
    /**
     *
     * @return Source
     */
    public function getSource()
    {
    	return $this->source;
    }
    
    
    public function addCategory(Category $category)
    {
    	$this->categories[] = $category;
    	return $this;
    }
    
    public function removeCategory(Category $category)
    {
    	$this->categories->removeElement($category);
    }
    
    public function getCategories()
    {
    	return $this->categories;
    }
    
    /**
     * Set zohoId
     *
     * @param string $zohoId
     *
     * @return Contact
     */
    public function setZohoId($zohoId)
    {
    	$this->zohoId = $zohoId;
    
    	return $this;
    }
    
    /**
     * Get zohoId
     *
     * @return string
     */
    public function getZohoId()
    {
    	return $this->zohoId;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    
    public function setCreatedAt($createdat)
    {
    	$this->createdAt = $createdat;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
    	return $this->updatedAt;
    }
    
    public function setUpdatedAt($updatedat)
    {
    	$this->updatedAt = $updatedat;
    }
    
    public function getAllCategories()
    {
    	$str = '';
    	$i=0;
    	foreach($this->getCategories() as $category)
    	{
    		if($i>0) $str .= '; ';
    		$str .= $category->getName();
    		$i++;
    	}
    	return $str;
    }
}
