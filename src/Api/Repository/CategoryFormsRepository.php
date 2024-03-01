<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\CategoryForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryForm>
 */
class CategoryFormsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryForm::class);
    }

    /**
     * @return string[][]
     */
    public function getAll(): array
    {
        $sql = <<<SQL
        SELECT      name,
                    french_name as "frenchName",
                    slug
        FROM        category_form
        WHERE       deleted_at IS NULL
        ORDER BY    order_number
        SQL;

        /** @var string[][] */
        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
    }
}
