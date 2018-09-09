<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Libs\ThumbnailsMaker;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=225)
     * 
     * @Assert\NotBlank
     * @Assert\Length(max=225)
     */
    private $title;
    
   /**
    * @ORM\Column(type="string", length=225)
    */
    private $slug;
    
     /**
     * @ORM\Column(type="text")
      * @Assert\NotBlank
     */
    private $content;
    
      /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    private $image = null;
    
    /**
    * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {
     *      "image/png",
 *          "image/jpeg",
 *          "image/jpg",
 *          "image/gif",},
     *     mimeTypesMessage = "Dopuszczalne formaty plików to: jpg, png lub gif!"
     * )
     */
    private $thumbnail;
    
    /**
     *
     * @ORM\ManyToOne(
     *  targetEntity="Category",
     *  inversedBy="articles"
     * 
     * )
     * 
     * @ORM\JoinColumn(
     *    name="category_id",
     *    referencedColumnName="id",
     *    onDelete = "SET NULL"
     * 
     * )
     * @Assert\NotBlank
     */
    private $category;
    
    /**
     *
     * @ORM\ManyToMany(
     *   targetEntity="Tag",
     *   inversedBy="articles"
     * )
     * 
     * @ORM\JoinColumn(
     *   name="arts_tags"
     * )
     */
    private $tags;
    
    /**
     *
     * @ORM\ManyToOne(
     *  targetEntity="User"
     * )
     * 
     * @ORM\JoinColumn(
     *  name="author_id",
     *  referencedColumnName="id"
     * )
     *  
     *  
     */
    private $author;
    
      /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
      /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedDate = null;
    
        public function __construct()
        {
            $this->createDate = new \DateTime();
            $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
                    
        }
    
       public function getId() {
          return $this->id;
      }   
    
      public function getTitle() {
          return $this->title;
      }

      public function getContent() {
          return $this->content;
      }

      public function getImage() {
          return $this->image;
      }
      
        public function getThumbnail() {
          return $this->thumbnail;
      }
      
      public function getCategory() {
          return $this->category;
      }
      
      public function getTags() {
          return $this->tags;
      }
      
     public function getPublishedDate() {
          return $this->publishedDate;
      }

      /**
       * 
       * @return User
       */
      public function getAuthor() {
          return $this->author;
      }

      public function getCreateDate() {
          return $this->createDate;
      }
      
       public function getSlug() {
          return $this->slug;
      }     

      public function setTitle($title) {
          $this->title = $title;
          return $this;
      }

      public function setContent($content) {
          $this->content = $content;
          return $this;
      }
      
      public function setCategory($category) {
          $this->category = $category;
          return $this;
      }
      
      public function addTag($tags)
      {
          $this->tags[] = $tags;
          return $this;
      }
      
      public function removeTag($tags)
      {
          $this->tags->removeElement($tags);
          return $this;
      }
      
      public function setTags($tags) {
          $this->tags = $tags;
          return $this;
      }

      public function setImage($image) {
          $this->image = $image;
          return $this;
      }
      
        public function setThumbnail($thumbnail) {
          $this->thumbnail = $thumbnail;
          return $this;
      }

      /**
       * 
       * @param User $author
       * @return $this
       */
      public function setAuthor($author) {
          $this->author = $author;
          return $this;
      }

      public function setCreateDate($createDate) {
          $this->date = $createDate;
          return $this;
      }
      
      public function setPublishedDate($publishedDate) {
          $this->publishedDate = $publishedDate;
          return $this;
      }
      
      public function setSlug($slug) {
          $this->slug = \App\Libs\Utils::sluggify($slug);
          return $this;
      }

      /**
       * @ORM\PrePersist
       * @ORM\PreUpdate
       */
      public function preSave()
      {
          if(null !== $this->thumbnail)
          {
            //  $ThumbnailsMaker = new ThumbnailsMaker($this->getThumbnail(), 300, 300, 'min-');
              //$this->setThumbnail($ThumbnailsMaker);
          }
      }
}
