<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index as Index;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @ORM\Table(name="videos",
 *      indexes={@Index(name="title_idx", columns={"title"})})
 */
class Video
{
    //public const videoForNotLoggedIn = 113716040; //vimeo video id
    public const videoForNotLoggedInOrNoMembers = 'https://player.vimeo.com/video/113716040'; //vimeo video id
    public const VimeoPath = 'https://player.vimeo.com/video/';
    public const perPage = 5; //pagination items on page
    public const uploadFolder = '/uploads/videos/';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="videos")
     * Issue with forgein key constraint can be solved like this and in the controller
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="video")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="likedVideos")
     * Default table name on many to many is created by symfony convention let's change a table name thisway:
     * @ORM\JoinTable(name="likes")
     */
    private $usersThatLike;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="dislikedVideos")
     * Default table name on many to many is created by symfony convention let's change a table name thisway:
     * @ORM\JoinTable(name="dislikes")
     */
    private $usersThatDontLike;

    /**
     * @Assert\NotBlank(message="Please upload the video as a MP4 file.")
     * @Assert\File(mimeTypes={"video/mp4"})
     */
    private $uploaded_video;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersThatLike = new ArrayCollection();
        $this->usersThatDontLike = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getVimeoId(): ?string
    {
        // if upload from local get this path: /uploads/videos/2136396.mp4
        if (strpos($this->path, self::uploadFolder) !== false) {
            return $this->path;
        }
        // else we get ann array and explode and return the last element this path: https://player.vimeo.com/video/289729765
        $array = explode('/', $this->getPath());
        return end($array);
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }
//    ? means that argument can be null or integer in this case
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVideo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getVideo() === $this) {
                $comment->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatLike(): Collection
    {
        return $this->usersThatLike;
    }

    public function addUsersThatLike(User $usersThatLike): self
    {
        if (!$this->usersThatLike->contains($usersThatLike)) {
            $this->usersThatLike[] = $usersThatLike;
        }

        return $this;
    }

    public function removeUsersThatLike(User $usersThatLike): self
    {
        if ($this->usersThatLike->contains($usersThatLike)) {
            $this->usersThatLike->removeElement($usersThatLike);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatDontLike(): Collection
    {
        return $this->usersThatDontLike;
    }

    public function addUsersThatDontLike(User $usersThatDontLike): self
    {
        if (!$this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike[] = $usersThatDontLike;
        }

        return $this;
    }

    public function removeUsersThatDontLike(User $usersThatDontLike): self
    {
        if ($this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike->removeElement($usersThatDontLike);
        }

        return $this;
    }

    public function getUploadedVideo()
    {
        return $this->uploaded_video;
    }

    public function setUploadedVideo( $uploaded_video): self
    {
        $this->uploaded_video = $uploaded_video;

        return $this;
    }
}
