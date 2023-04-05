<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Addresses
        for ($a = 0; $a <= 10; $a++){
            $author = new Author();

            $author->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setSexe($faker->randomElement(['M', 'F']));

            $manager->persist($author);
        }
        $manager->flush();


        $allAuthors = $manager->getRepository(Author::class)->findAll();
        // Publisher
        for ($p = 0; $p <= 2; $p++){
            $publisher = new Publisher();

            $publisher->setName($faker->company());

            $manager->persist($publisher);
        }
        $manager->flush();

        $allPublishers = $manager->getRepository(Publisher::class)->findAll();

        // Books
        for ($b= 0; $b <= 30; $b++){
            $book = new Book();

            $book->setTitle($faker->sentence(6))
                ->setYear($faker->year())
                ->setIsbn($faker->isbn13())
                ->setDescription($faker->paragraph())
                ->setAuthorId($faker->randomElement($allAuthors))
                ->setPublisherId($faker->randomElement($allPublishers));

            $manager->persist($book);
        }
        $manager->flush();
        // Users
        for ($u = 0; $u <= 5; $u++){
            $user = new User();
            // hash password
            $hash = $this->hasher->hashPassword($user, "password");

            if($u === 0){
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail("admin@test.test");
            }else{
                $user->setEmail($faker->safeEmail());
            }

            $user->setPassword($hash);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
