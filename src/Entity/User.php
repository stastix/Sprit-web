<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Email(message: 'L\'adresse email "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(name:"Role", type:"string", columnDefinition:"ENUM('ADMIN', 'GUIDE', 'CLIENT')")]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 4, minMessage: 'Le nom doit comporter au moins {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 4, minMessage: 'Le prenom doit comporter au moins {{ limit }} caractères')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 8, minMessage: 'Le numéro doit comporter au moins {{ limit }} chiffres')]
    #[Assert\Regex(
        pattern: '/^[295]\d{7}$/',
        message: 'Le numéro doit commencer par 2, 9 ou 5 et être suivi de 7 chiffres.'
    )]
    private ?string $numeroTelephone = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    private ?string $genre = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = true;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

   

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        
        if($this->role == 'ADMIN'){
            $roles[] = 'ROLE_ADMIN';
        }elseif($this->role == 'CLIENT'){   
            $roles[] = 'ROLE_CLIENT';
        }elseif($this->role == 'GUIDE'){   
            $roles[] = 'ROLE_GUIDE';
        }
        // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {

        if($this->role == 'ADMIN'){
            $roles[] = 'ROLE_ADMIN';
        }elseif($this->role == 'CLIENT'){   
            $roles[] = 'ROLE_CLIENT';
        }elseif($this->role == 'GUIDE'){   
            $roles[] = 'ROLE_GUIDE';
        }
        $this->roles = $roles;

        return $this;
    }


    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->motDePasse;    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getRole(): ?string
    {
        return $this->role;

    }

    
    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }



    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(?string $numeroTelephone): static
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
