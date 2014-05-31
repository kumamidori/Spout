<?php

namespace Mackstar\Spout\Admin\Resource\App\Resources;

use BEAR\Resource\ResourceObject;
use BEAR\Package\Module\Database\Dbal\Setter\DbSetterTrait;
use BEAR\Sunday\Annotation\Db;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * FieldTypes
 *
 * @Db
 */
class Detail extends ResourceObject
{

    use DbSetterTrait;
    use ResourceInject;

    protected $table = 'resources';

    public function onGet($id = null)
    {
        $sql = "SELECT {$this->table}.* FROM {$this->table} WHERE {$this->table}.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        $resource = $stmt->fetch();

        try {
            $resource['type'] = $this->resource->get->uri('app://self/resources/types')
                ->eager
                ->withQuery(['slug' => $resource['type']])
                ->request()
                ->body['type'];
        } catch (\Exception $e) {
            var_dump("error:");
            echo($e->getTraceAsString());
        }

        $resource['title_label'] = $resource['type']['title_label'];
        $fieldTypes = [];
        $map = [];
        $resource['fields'] = [];
        foreach ($resource['type']['fields'] as $resourceField) {
            $map[$resourceField['id']] = $resourceField['slug'];
            $fieldType = $resourceField['field_type'];
            $slug = $resourceField['slug'];
            $resource['fields'][$slug] = [
                'label' => $resourceField['label'],
                'value' => ''
            ];
            if ($resourceField['multiple'] == '1') {
                $resource['fields'][$slug]['values'] = [];
                unset($resource['fields'][$slug]['value']);
            }
            $resource['fields']['slug'] = '';
            if (!in_array($fieldType, $fieldTypes)) {
                $fieldTypes[] = $fieldType;
            }
        }
        foreach ($fieldTypes as $fieldType) {
            $table = 'field_values_' . $fieldType;
            $sql = "SELECT $table.* FROM $table WHERE $table.`resource_id` = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            $fieldTypeRows[] = $stmt->fetchAll();
        }

        foreach ($fieldTypeRows as $rows) {
            foreach ($rows as $row) {
                $slug = $map[$row['resource_field_id']];
                $value = isset($row['value'])? $row['value'] : $row;

                if (isset($resource['fields'][$slug]['values'])) {
                    $resource['fields'][$slug]['values'][] = $value ;
                } else {
                    $resource['fields'][$slug]['value'] = $value;
                }
            }
        }
        $this['_model'] = 'resource';
        $this['resource'] = $resource;
        return $this;
    }
}
