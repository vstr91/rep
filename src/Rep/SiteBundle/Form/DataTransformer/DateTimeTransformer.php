<?php
namespace Rep\SiteBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Description of DateTimeTransformer
 *
 * @author Almir
 */
class DateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (DateTime) to a string.
     *
     * @param  DateTime|null $datetime
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }

        return $datetime->format('d/m/Y H:i:s');
    }

    /**
     * Transforms a string to an object (DateTime).
     *
     * @param  string $datetime
     * @return DateTime|null
     */
    public function reverseTransform($datetime)
    {
        // datetime optional
        if (!$datetime) {
            return;
        }

        return date_create_from_format('d/m/Y H:i:s', $datetime, new \DateTimeZone('America/Sao_Paulo'));
    }
}