<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @UniqueEntity("email", ignoreNull=true)
 * @UniqueEntity("zohoId", ignoreNull=true)
 * @UniqueEntity("name")
 */
class Company
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
     * * @ORM\OneToMany(targetEntity="Contact", mappedBy="company")
     */
    private $contacts;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=50, nullable=true)
     */
    private $website;
    
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
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_prospect", type="boolean", nullable=false)
     */
    private $isProspect;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", cascade={"persist"})
     */
    private $categories;

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
    	$this->contacts = new ArrayCollection();
    }
    
    /**
     * Get the name of the company.
     *
     * @return string
     */
    public function __toString()
    {
    	return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Company
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
     * Set fax
     *
     * @param string $fax
     *
     * @return Company
     */
    public function setFax($fax)
    {
    	$this->fax = $fax;
    
    	return $this;
    }
    
    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
    	return $this->fax;
    }
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return Company
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
     * Set website
     *
     * @param string $website
     *
     * @return Company
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
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
     * @return Company
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
     * @return Company
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
     * Set postalcode
     *
     * @param string $postalcode
     *
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @param string $description
     *
     * @return Company
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set isProspect
     *
     * @param boolean $isProspect
     *
     * @return Company
     */
    public function setIsProspect($isProspect)
    {
    	$this->isProspect = $isProspect;
    
    	return $this;
    }
    
    /**
     * Get isProspect
     *
     * @return boolean
     */
    public function getIsProspect()
    {
    	return $this->isProspect;
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
     * Return all contact associated to the company.
     *
     * @return Contact[]
     */
    public function getContacts()
    {
    	return $this->contacts;
    }
    
    /**
     * Set all contacts in the company.
     *
     * @param Contact[] $contacts
     */
    public function setContacts($contacts)
    {
    	$this->contacts->clear();
    	$this->contacts = new ArrayCollection($contacts);
    }
    
    /**
     * Add a contact in the company.
     *
     * @param $contact Contact The contact to associate
     */
    public function addContact($contact)
    {
    	if ($this->contacts->contains($contact)) {
    		return;
    	}
    
    	$this->contacts->add($contact);
    	$contact->setCompany($this);
    }
    
    /**
     * @param Contact $contact
     */
    public function removeContact($contact)
    {
    	if (!$this->contacts->contains($contact)) {
    		return;
    	}
    
    	$this->contacts->removeElement($contact);
    	$contact->removeCompany($this);
    }
    
    /**
     * Set zohoId
     *
     * @param string $zohoId
     *
     * @return Company
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

