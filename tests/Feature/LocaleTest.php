<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('locale can be switched to bangla', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('locale.update'), ['locale' => 'bn'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('locale', 'bn');
});

test('invalid locales are rejected', function () {
    $this->from(route('dashboard'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertSessionHasErrors('locale');
});

test('bangla locale is applied and translations are shared', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['locale' => 'bn'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', 'bn')
            ->where('translations.Dashboard', 'ড্যাশবোর্ড'));
});

test('english is the default locale without translations', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', 'en')
            ->where('translations', []));
});
