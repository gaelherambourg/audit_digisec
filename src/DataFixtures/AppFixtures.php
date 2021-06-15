<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Societe;
use App\Entity\Utilisateur;
use App\Services\ImportCsvServices;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class AppFixtures.
 * Classe permettant la création de jeux de données aléatoires.
 * @package App\DataFixtures
 * @note composer req --dev make doctrine/doctrine-fixtures-bundle (Bundle Fixtures)
 * @note composer require --dev fzaninotto/faker (Bundle Faker)
 */
class AppFixtures extends Fixture
{
    // Constantes :
    // Mot de passe des Fixtures
    const PLAIN_PASSWORD = "azerty";

    /**
     * Méthode de chargement des jeux de données dans chacune des tables du projet. 
     * @note cmd : "php bin/console doctrine:fixture:load"
     * @param ObjectManager $manager - ObjectManager de la couche d'accès aux données (via l'ORM Doctrine).
     */
    public function load(ObjectManager $manager)
    {
        // Utilisation du Bundle FakerPhp
        $faker = Factory::create("fr_FR");

        $pwd = password_hash(self::PLAIN_PASSWORD, PASSWORD_BCRYPT);

        $utilisateur = new Utilisateur();
        $utilisateur
            ->setEmail('gael@digisec.fr')
            ->setRoles([])
            ->setPassword($pwd)
            ->setPrenom('Gaël')
            ->setNom('Repillez')
            ->setAdmin(true)
            ->setUsername('GaëlRepillez');
        $manager->persist($utilisateur);
        $manager->flush();

        $adresse = new Adresse();
        $adresse
            ->setLibelle('Adresse principale')
            ->setRue('9 rue du Fresche Blanc')
            ->setCodePostal('44300')
            ->setVille('Nantes');

        $contact = new Contact();
        $contact
            ->setNomContact('Repillez')
            ->setPrenomContact('Gaël')
            ->setTelContact('06 66 21 14 76')
            ->setEmailContact('gael@digisec.fr')
            ->setPosteContact('Gérant');

        $societe = new Societe();
        $societe
            ->setNom('DIGISEC')
            ->setRaisonSocial('Digisec')
            ->setSiret('89308328700019')
            ->setImmatRcs('Nantes B 893 083 287')
            ->setTypeEntreprise('SARL unipersonnelle')
            ->setCapitalSocial(1000)
            ->setDateCreation(new \DateTime())
            ->setEstDigisec(true)
            ->addAdresse($adresse)
            ->addContact($contact);
        $manager->persist($societe);
        $manager->flush();

        for ($i = 0; $i < 4; $i++) {

            $adresse = new Adresse();
            $adresse
                ->setLibelle('Libelle ' . $i)
                ->setRue($faker->streetAddress)
                ->setCodePostal($faker->numberBetween(100, 9599) * 10)
                ->setVille($faker->city);

            $adresseUn = new Adresse();
            $adresseUn
                ->setLibelle('Libelle ' . $i)
                ->setRue($faker->streetAddress)
                ->setCodePostal($faker->numberBetween(100, 9599) * 10)
                ->setVille($faker->city);

            $contact = new Contact();
            $contact
                ->setNomContact($faker->name)
                ->setPrenomContact($faker->firstName)
                ->setTelContact($faker->phoneNumber)
                ->setEmailContact($faker->email)
                ->setPosteContact($faker->jobTitle);

            $contactUn = new Contact();
            $contactUn
                ->setNomContact($faker->name)
                ->setPrenomContact($faker->firstName)
                ->setTelContact($faker->phoneNumber)
                ->setEmailContact($faker->email)
                ->setPosteContact($faker->jobTitle);

            $societe = new Societe();
            $societe
                ->setNom($faker->company)
                ->setRaisonSocial($faker->company)
                ->setSiret('Siret ' . $i)
                ->setImmatRcs('Immat ' . $i)
                ->setTypeEntreprise($faker->catchPhrase)
                ->setCapitalSocial(10000)
                ->setDateCreation(new \DateTime())
                ->setEstDigisec(false)
                ->addAdresse($adresse)
                ->addAdresse($adresseUn)
                ->addContact($contact)
                ->addContact($contactUn);
            $manager->persist($societe);
            $manager->flush();
        }
    }
}
