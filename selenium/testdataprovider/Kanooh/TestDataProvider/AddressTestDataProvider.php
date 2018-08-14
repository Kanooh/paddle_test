<?php

/**
 * @file
 * Contains \Kanooh\TestDataProvider\AddressTestDataProvider.
 */

namespace Kanooh\TestDataProvider;

class AddressTestDataProvider extends TestDataProvider
{
    /**
     * {@inheritdoc}
     */
    public function getValidDataSet()
    {
        return array(
            array(
              'country' => 'BE',
              'thoroughfare' => 'Ravensteingalerij 4',
              'locality' => 'Brussel',
              'postal_code' => '1000'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Carel van Manderstraat',
              'locality' => 'Antwerpen',
              'postal_code' => '2050'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Rooienberg 25',
              'locality' => 'Duffel',
              'postal_code' => '2570'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Industriezone TTS',
              'locality' => 'Temse',
              'postal_code' => '9140'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Oud-Oosteinde 1',
              'locality' => 'Axel',
              'postal_code' => '4571'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Diestsesteenweg 8',
              'locality' => 'Herk-de-Stad',
              'postal_code' => '3540'
            ),
            array(
              'country' => 'BE',
              'thoroughfare' => 'Diestersteenweg 212b',
              'locality' => 'Hasselt',
              'postal_code' => '3510'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidDataSet()
    {
        return array(
            array(
                'country' => 'BE',
                'thoroughfare' => 'Dedenstreet 100',
                'locality' => 'Planet namek',
                'postal_code' => '9001'
            ),
            array(
                'country' => 'BE',
                'thoroughfare' => 'Friza 1',
                'locality' => 'Planet Vegeta',
                'postal_code' => '9002'
            )
        );
    }

    /**
     * Generate a random valid postal address.
     */
    public function getValidValue()
    {
        $addresses =  array(
          array(
            'country' => 'BE',
            'thoroughfare' => 'Carel van Manderstraat',
            'locality' => 'Antwerpen',
            'postal_code' => '2050'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Ravensteingalerij 4',
            'locality' => 'Brussel',
            'postal_code' => '1000'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Rooienberg 25',
            'locality' => 'Duffel',
            'postal_code' => '2570'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Industriezone TTS',
            'locality' => 'Temse',
            'postal_code' => '9140'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Oud-Oosteinde 1',
            'locality' => 'Axel',
            'postal_code' => '4571'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Diestsesteenweg 8',
            'locality' => 'Herk-de-Stad',
            'postal_code' => '3540'
          ),
          array(
            'country' => 'BE',
            'thoroughfare' => 'Diestersteenweg 212b',
            'locality' => 'Hasselt',
            'postal_code' => '3510'
          ),
        );
        
        
        return $addresses[rand(0, 6)];
    }
}
