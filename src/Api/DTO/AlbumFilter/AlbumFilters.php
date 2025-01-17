<?php

declare(strict_types=1);

namespace App\Api\DTO\AlbumFilter;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AlbumFilters
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public AlbumFilterValues $primaryTypes,
        public AlbumFilterValues $secondaryTypes,
        public AlbumFilterValues $anyTypes,
        public AlbumFilterValues $categoryForms,
        public AlbumFilterValues $regionalForms,
        public AlbumFilterValues $specialForms,
        public AlbumFilterValues $variantForms,
        public AlbumFilterValues $catchStates,
        public AlbumFilterValues $originalGameBundles,
        public AlbumFilterValues $gameBundleAvailabilities,
        public AlbumFilterValues $gameBundleShinyAvailabilities,
        public AlbumFilterValues $families,
        public AlbumFilterValues $collectionAvailabilities,
    ) {}

    /**
     * @param string[][] $data
     */
    public static function createFromArray(array $data): self
    {
        $resolver = new OptionsResolver();
        self::configureOptions($resolver);
        $options = $resolver->resolve($data);

        return new self(
            $options['primaryTypes'],
            $options['secondaryTypes'],
            $options['anyTypes'],
            $options['categoryForms'],
            $options['regionalForms'],
            $options['specialForms'],
            $options['variantForms'],
            $options['catchStates'],
            $options['originalGameBundles'],
            $options['gameBundleAvailabilities'],
            $options['gameBundleShinyAvailabilities'],
            $options['families'],
            $options['collectionAvailabilities'],
        );
    }

    public static function configureOptions(OptionsResolver $resolver): void
    {
        $defaultsValues = [
            'primaryTypes' => [],
            'secondaryTypes' => [],
            'anyTypes' => [],
            'categoryForms' => [],
            'regionalForms' => [],
            'specialForms' => [],
            'variantForms' => [],
            'catchStates' => [],
            'originalGameBundles' => [],
            'gameBundleAvailabilities' => [],
            'gameBundleShinyAvailabilities' => [],
            'families' => [],
            'collectionAvailabilities' => [],
        ];

        $resolver->setDefaults($defaultsValues);

        foreach (array_keys($defaultsValues) as $key) {
            $resolver->setNormalizer(
                $key,
                function (Options $options, array $data): AlbumFilterValues {
                    unset($options); // To remove PHPMD.UnusedFormalParameter warning

                    return self::normalizer($data);
                }
            );
        }
    }

    /**
     * @param string[] $data
     */
    public static function normalizer(array $data): AlbumFilterValues
    {
        // Remove empty value
        $cleanData = array_filter($data);

        // Replace string null to null
        $newData = array_map(
            fn ($value) => ('null' == $value) ? null : $value,
            $cleanData
        );

        return new AlbumFilterValues($newData);
    }
}
