<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @UniqueEntity("name")
 */
class Category
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
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * The category parent.
     *
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    private $parent;
    
    /**
     * Contacts in the category.
     *
     * @var Contact[]
     * @ORM\ManyToMany(targetEntity="Contact", mappedBy="categories")
     **/
    private $contacts;
    
    /**
     * Companies in the category.
     *
     * @var Company[]
     * @ORM\ManyToMany(targetEntity="Company", mappedBy="categories")
     **/
    private $companies;
    
    
    
    public function __construct()
    {
    	$this->contacts = new ArrayCollection();
    }
    
    /**
     * Get the name of the category.
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
    	return $this->updatedAt;
    }
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
    
    public function setParent(Category $parent=NULL)
    {
    	$this->parent = $parent;
    	return $this;
    }
    
    
    public function getParent()
    {
    	return $this->parent;
    }
    
    /**
     * Return all contact associated to the category.
     *
     * @return Contact[]
     */
    public function getContacts()
    {
    	return $this->contacts;
    }
    
    /**
     * Set all contacts in the category.
     *
     * @param Contact[] $contacts
     */
    public function setContacts($contacts)
    {
    	$this->contacts->clear();
    	$this->contacts = new ArrayCollection($contacts);
    }
    
    /**
     * Add a contact in the category.
     *
     * @param Contact $contact The contact to associate
     */
    public function addContact($contact)
    {
    	if ($this->contacts->contains($contact)) {
    		return;
    	}
    
    	$this->contacts->add($contact);
    	$contact->addCategory($this);
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
    	$contact->removeCategory($this);
    }
    
    /**
     * Return all company associated to the category.
     *
     * @return Company[]
     */
    public function getCompanies()
    {
    	return $this->companies;
    }
    
    /**
     * Set all companies in the category.
     *
     * @param Company[] $companies
     */
    public function setCompanies($companies)
    {
    	$this->companies->clear();
    	$this->companies = new ArrayCollection($companies);
    }
    
    /**
     * Add a company in the category.
     *
     * @param Company $company The company to associate
     */
    public function addCompany($company)
    {
    	if ($this->companies->contains($company)) {
    		return;
    	}
    
    	$this->companies->add($company);
    	$company->addCategory($this);
    }
    
    /**
     * @param Company $company
     */
    public function removeCompany($company)
    {
    	if (!$this->companies->contains($company)) {
    		return;
    	}
    
    	$this->companies->removeElement($company);
    	$company->removeCategory($this);
    }
}

