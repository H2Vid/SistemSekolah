<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $web = [
            [
                'name' => 'Home',
                'on' => 'web',
                'type' => 'item',
                'route' => 'home',
                'icon' => '#',
                'ordering' => 1,
            ],
            [
                'name' => 'Profile Sekolah',
                'on' => 'web',
                'type' => 'item',
                'route' => '#',
                'icon' => '#',
                'ordering' => 2,
            ],
            [
                'name' => 'Guru',
                'on' => 'web',
                'type' => 'item',
                'route' => 'guru',
                'icon' => '#',
                'ordering' => 3,
            ],
            [
                'name' => 'Galeri',
                'on' => 'web',
                'type' => 'item',
                'route' => 'galeri',
                'icon' => '#',
                'ordering' => 4,
            ],
            [
                'name' => 'Blog',
                'on' => 'web',
                'type' => 'item',
                'route' => 'blog',
                'icon' => '#',
                'ordering' => 5,
            ],
            [
                'name' => 'PPDB',
                'on' => 'web',
                'type' => 'item',
                'route' => 'ppdb',
                'icon' => '#',
                'ordering' => 6,
            ],
        ];

        // Create parent menus
        Menu::insert([
            [
                'name' => 'Dashboard',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.dashboard',
                'icon' => 'bi bi-grid-fill',
                'ordering' => 1,
            ],
            [
                'name' => 'Master Data',
                'on' => 'cms',
                'type' => 'item',
                'route' => '#',
                'icon' => 'bi bi-server',
                'ordering' => 46,
            ],
            [
                'name' => 'Gallery',
                'on' => 'cms',
                'type' => 'item',
                'route' => '#',
                'icon' => 'bi bi-columns-gap',
                'ordering' => 47,
            ],
            [
                'name' => 'Article',
                'on' => 'cms',
                'type' => 'item',
                'route' => '#',
                'icon' => 'bi bi-newspaper',
                'ordering' => 48,
            ],
            [
                'name' => 'Website Profile',
                'on' => 'cms',
                'type' => 'item',
                'route' => '#',
                'icon' => 'bi bi-browser-chrome',
                'ordering' => 49,
            ],
            [
                'name' => 'Setting',
                'on' => 'cms',
                'type' => 'item',
                'route' => '#',
                'icon' => 'bi bi-gear',
                'ordering' => 50,
            ],
            ...$web,
        ]);

        // Create child master data menus
        $master_data = Menu::where('name', 'Master Data')->first();

        Menu::insert([
            [
                'name' => 'PPDB',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.master.ppdb',
                'icon' => '#',
                'parent_id' => $master_data->id,
                'ordering' => 1,
            ],
        ]);

        // Create child gallery menus
        $gallery = Menu::where('name', 'Gallery')->first();

        // Category and Content
        Menu::insert([
            [
                'name' => 'Kategori',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.gallery.category',
                'icon' => '#',
                'parent_id' => $gallery->id,
                'ordering' => 1,
            ],
            [
                'name' => 'Konten',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.gallery.content',
                'icon' => '#',
                'parent_id' => $gallery->id,
                'ordering' => 2,
            ],
        ]);

        // Create child article menus
        $article = Menu::where('name', 'Article')->first();

        Menu::insert([
            [
                'name' => 'Kategori',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.article.category',
                'icon' => '#',
                'parent_id' => $article->id,
                'ordering' => 1,
            ],
            [
                'name' => 'Konten',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.article.content',
                'icon' => '#',
                'parent_id' => $article->id,
                'ordering' => 2,
            ],
        ]);

        // Create child website profile menus
        $website_profile = Menu::where('name', 'Website Profile')->first();

        Menu::insert([
            [
                'name' => 'Hero',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.hero',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 1,
            ],
            [
                'name' => 'Tagline',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.tagline',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 2,
            ],
            [
                'name' => 'Tentang Kami',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.about-us',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 3,
            ],
            [
                'name' => 'Jabatan',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.position',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 4,
            ],
            [
                'name' => 'Staff',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.staff',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 5,
            ],
            [
                'name' => 'Program Keahlian',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.major',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 6,
            ],
            [
                'name' => 'Ekstrakurikuler',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.extracurricular',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 7,
            ],
            [
                'name' => 'Testimonial Alumni',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.testimonial',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 8,
            ],
            [
                'name' => 'Partner',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.partner',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 9,
            ],
            [
                'name' => 'Fasilitas',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.website.facility',
                'icon' => '#',
                'parent_id' => $website_profile->id,
                'ordering' => 10,
            ],
        ]);

        // Create child setting menus
        $setting = Menu::where('name', 'Setting')->first();

        Menu::insert([
            [
                'name' => 'Menu',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.setting.menu',
                'icon' => '#',
                'parent_id' => $setting->id,
                'ordering' => 1,
            ],
            [
                'name' => 'Role',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.setting.role',
                'icon' => '#',
                'parent_id' => $setting->id,
                'ordering' => 2,
            ],
            [
                'name' => 'User',
                'on' => 'cms',
                'type' => 'item',
                'route' => 'cms.setting.user',
                'icon' => '#',
                'parent_id' => $setting->id,
                'ordering' => 3,
            ],
        ]);

        // Create child Profile Sekolah web menus
        $profile_sekolah = Menu::where('name', 'Profile Sekolah')->first();

        Menu::insert([
            [
                'name' => 'Tentang Kami',
                'on' => 'web',
                'type' => 'item',
                'route' => 'tentang-kami',
                'icon' => '#',
                'parent_id' => $profile_sekolah->id,
                'ordering' => 1,
            ],
            [
                'name' => 'Fasilitas',
                'on' => 'web',
                'type' => 'item',
                'route' => 'fasilitas',
                'icon' => '#',
                'parent_id' => $profile_sekolah->id,
                'ordering' => 2,
            ],
            [
                'name' => 'Program Keahlian',
                'on' => 'web',
                'type' => 'item',
                'route' => 'program-keahlian',
                'icon' => '#',
                'parent_id' => $profile_sekolah->id,
                'ordering' => 3,
            ],
            [
                'name' => 'Ekstrakurikuler',
                'on' => 'web',
                'type' => 'item',
                'route' => 'ekstrakulikuler',
                'icon' => '#',
                'parent_id' => $profile_sekolah->id,
                'ordering' => 4,
            ],
        ]);
    }
}
