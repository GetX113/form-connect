<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormateurRepository")
 */
class Formateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rank;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vote;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active_admin = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active_email = false;

    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="formateur")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="formateur")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Disponibilite", mappedBy="formateur")
     */
    private $disponibilites;

    /**
     * @ORM\Column(type="string", length=4068, nullable=true)
     */
    private $date_disponibilite;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $frais_repas;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $frais_hotel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $frais_deplacement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormationFormateur", mappedBy="formateur")
     */
    private $experience;

    /**
     * @ORM\Column(type="string", length=4068, nullable=true)
     */
    private $departement_disponibilite;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $classement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="formateur", orphanRemoval=true)
     */
    private $reservations;



    

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
        $this->experience = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse1(): ?string
    {
        return $this->adresse1;
    }

    public function setAdresse1(string $adresse1): self
    {
        $this->adresse1 = $adresse1;

        return $this;
    }

    public function getAdresse2(): ?string
    {
        return $this->adresse2;
    }

    public function setAdresse2(?string $adresse2): self
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRank(): ?float
    {
        return $this->rank;
    }

    public function setRank(?float $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin()
    {
        $this->last_login = new \DateTime("now");

        return $this;
    }

    public function getActiveAdmin(): ?bool
    {
        return $this->active_admin;
    }

    public function setActiveAdmin(bool $active_admin): self
    {
        $this->active_admin = $active_admin;

        return $this;
    }

    public function getActiveEmail(): ?bool
    {
        return $this->active_email;
    }

    public function setActiveEmail(bool $active_email): self
    {
        $this->active_email = $active_email;

        return $this;
    }


    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setFormateur($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getFormateur() === $this) {
                $notification->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setFormateur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getFormateur() === $this) {
                $message->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Disponibilite[]
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites[] = $disponibilite;
            $disponibilite->setFormateur($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->removeElement($disponibilite);
            // set the owning side to null (unless already changed)
            if ($disponibilite->getFormateur() === $this) {
                $disponibilite->setFormateur(null);
            }
        }

        return $this;
    }

    public function getDateDisponibilite(): ?string
    {
        return $this->date_disponibilite;
    }

    public function setDateDisponibilite(?string $date_disponibilite): self
    {
        $this->date_disponibilite = $date_disponibilite;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getFix(): ?string
    {
        return $this->fix;
    }

    public function setFix(?string $fix): self
    {
        $this->fix = $fix;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFraisRepas(): ?int
    {
        return $this->frais_repas;
    }

    public function setFraisRepas(?int $frais_repas): self
    {
        $this->frais_repas = $frais_repas;

        return $this;
    }

    public function getFraisHotel(): ?int
    {
        return $this->frais_hotel;
    }

    public function setFraisHotel(?int $frais_hotel): self
    {
        $this->frais_hotel = $frais_hotel;

        return $this;
    }

    public function getFraisDeplacement(): ?string
    {
        return $this->frais_deplacement;
    }

    public function setFraisDeplacement(?string $frais_deplacement): self
    {
        $this->frais_deplacement = $frais_deplacement;

        return $this;
    }

    /**
     * @return Collection|FormationFormateur[]
     */
    public function getExperience(): Collection
    {
        return $this->experience;
    }

    public function addExperience(FormationFormateur $experience): self
    {
        if (!$this->experience->contains($experience)) {
            $this->experience[] = $experience;
            $experience->setFormateur($this);
        }

        return $this;
    }

    public function removeExperience(FormationFormateur $experience): self
    {
        if ($this->experience->contains($experience)) {
            $this->experience->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getFormateur() === $this) {
                $experience->setFormateur(null);
            }
        }

        return $this;
    }

    public function getDepartementDisponibilite(): ?string
    {
        return $this->departement_disponibilite;
    }

    public function setDepartementDisponibilite(?string $departement_disponibilite): self
    {
        $this->departement_disponibilite = $departement_disponibilite;

        return $this;
    }

    public function getClassement(): ?int
    {
        return $this->classement;
    }

    public function setClassement(?int $classement): self
    {
        $this->classement = $classement;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setFormateur($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getFormateur() === $this) {
                $reservation->setFormateur(null);
            }
        }

        return $this;
    }


}
