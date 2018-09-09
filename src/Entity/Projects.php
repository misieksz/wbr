<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Libs\FileUploader;
use App\Libs\ThumbnailsMaker;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectsRepository")
 * @ORM\Table(name="projects")
 * @ORM\HasLifecycleCallbacks
 */
class Projects {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=225)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=225)
     */
    private $slug;

    /**
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];
    
    private $imagesTmp;
    
    /**
     * 
     * 
     * 
     * @Assert\All({
     *     
     *     @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {
     *      "image/png",
 *          "image/jpeg",
 *          "image/jpg",
 *          "image/gif",},
     *     mimeTypesMessage = "Dopuszczalne formaty plików to: jpg, png lub gif!"
     * )
     * })
     */
    private $files;

    /**
     *
     * @var string
     */
    private $path;

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getContent() {
        return $this->content;
    }

    public function getImages() {
        return $this->images;
    }

    public function getFiles() {
        return $this->files;
    }
    
    public function getPath() {
        return $this->path;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setSlug($slug) {
        $this->slug = \App\Libs\Utils::sluggify($slug);
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setImages($images) {
        $this->images = $images;
        return $this;
    }

    public function setFiles($files) {
        $this->files = $files;
        return $this;
    }
    
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    public function addImages($images) {
        $this->images[] = $images;
        return $this;
    }

    

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave() {


        if (null === $this->slug) {
            $this->setSlug($this->getTitle());
        }

      
    }
    
         
         
}