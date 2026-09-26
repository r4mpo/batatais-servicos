<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_is_public_and_linked_from_navigation_and_footer(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk()
            ->assertSee('Sobre a SpiderSoft', false)
            ->assertSee('Eric Isaias Tomasini', false)
            ->assertSee('Com grandes tecnologias, vêm grandes inovações.', false);

        $this->assertSame(
            2,
            substr_count($response->getContent(), 'href="'.route('about').'"')
        );
    }
}
