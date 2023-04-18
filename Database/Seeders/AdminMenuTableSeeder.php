<?php

namespace Modules\Faq\Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faqAdminMenu = [
            ['label' => 'FAQ', 'menu' => '', 'link' => '', 'children' => [
                ['label' => 'FAQ 목록 관리', 'menu' => 'admin.faq', 'link' => 'admin.faq.index'],
                ['label' => 'FAQ 카테고리 관리', 'menu' => 'admin.faq-category', 'link' => 'admin.faq-category.index'],
            ]]
        ];

        $maxPosition = AdminMenu::whereNull('parent_id')->max('position');

        foreach ($faqAdminMenu as $parent) {
            $parentId = AdminMenu::create([
                'label' => $parent['label'],
                'menu' => $parent['menu'],
                'link' => $parent['link'],
                'position' => $maxPosition + 1,
            ])->id;

            foreach ($parent['children'] as $cIdx => $child) {
                AdminMenu::create([
                    'parent_id' => $parentId,
                    'label' => $child['label'],
                    'menu' => $child['menu'],
                    'link' => $child['link'],
                    'position' => $cIdx + 1,
                ]);
            }
        }
    }
}
