<?php

namespace Mackstar\Spout\Admin\Resource\App\Menus;

use BEAR\Resource\ResourceObject;
use BEAR\Package\Module\Database\Dbal\Setter\DbSetterTrait;
use BEAR\Sunday\Annotation\Db;

/**
 * Add
 *
 * @Db
 */
class Links extends ResourceObject{

    use DbSetterTrait;

    protected $table = 'links';

    public function onPost($menu, $name, $url, $type)
    {
        $this->db->insert($this->table, [
            'menu' => $menu,
            'name' => $name,
            'url' => $url,
            'type' => $type
        ]);
        return $this;

    }

    public function onGet($menu, $id = null)
    {

        if ($id) {
            $stmt = $this->db->executeQuery("SELECT * FROM {$this->table} WHERE id = ?", array($id));
            $this['link'] = $stmt->fetch();
        }
        if (!$id) {
            $stmt = $this->db->executeQuery("SELECT * FROM {$this->table} WHERE menu = ?", array($menu));
            $this['links'] = $stmt->fetchAll();
        }

        return $this;
    }

    public function onDelete($id, $menu = null)
    {
        $removal = ['id' => $id];
        if ($menu) {
            $removal = ['menu' => $menu];
        }
        $this->db->delete($this->table, $removal);
        $this->code = 204;
        return $this;
    }

    public function onPut($id, $name, $url)
    {
        $this->db->update($this->table, ['name' => $name, 'url' => $url], ['id' => $id]);
    }


}