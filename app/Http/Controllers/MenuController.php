<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('ind_ativo', 'S')
            ->orderBy('id_menu_pai')
            ->orderBy('id_sequencia')
            ->get();

        $rootMenus = $menus->where('id_menu_pai', 0);

        $tree = $rootMenus->map(function ($menu) use ($menus) {
            return $this->mapMenu($menu, $menus);
        });

        return response()->json($tree->values());
    }

    private function mapMenu($menu, $allMenus)
    {
        $item = [
            'title' => $menu->nome,
        ];

        if (!empty($menu->url)) {
            $item['href'] = $menu->url;
        } elseif (!empty($menu->modulo)) {
            $to = ['name' => $menu->modulo];
            if (!empty($menu->parametro)) {
                $json = json_decode($menu->parametro, true);
                if (is_array($json)) {
                    $to['params'] = $json;
                }
            }
            $item['to'] = $to;
        }

        $children = $allMenus->where('id_menu_pai', $menu->id);
        if ($children->isNotEmpty()) {
            $item['submenu'] = $children->map(function ($child) use ($allMenus) {
                return $this->mapMenu($child, $allMenus);
            })->values();
        }

        return $item;
    }
}
