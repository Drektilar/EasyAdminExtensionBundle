<?php

namespace AlterPHP\EasyAdminExtensionBundle\Tests\Controller;

use AlterPHP\EasyAdminExtensionBundle\Tests\Fixtures\AbstractTestCase;

class AutocompleteAddTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->initClient(['environment' => 'autocomplete_add']);
    }

    public function testNewEntityAutocompleteModal()
    {
        $crawler = $this->requestNewView('Product');

        $this->assertSame(200, static::$client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('select#product_category_autocomplete[data-easyadmin-autocomplete-create-action-url="/admin/?action=newAjax&entity=Category"]')->count());

        $crawlerAjax = $this->requestNewAjaxView('Category');
        $form = $crawlerAjax->filter('form[name=category]')->form(
            ['category[name]' => 'New Ajax Category']
        );
        $crawlerAjax = static::$client->submit($form);

        $this->assertSame(200, static::$client->getResponse()->getStatusCode());
    }
}
