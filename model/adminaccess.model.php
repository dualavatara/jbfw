<?php

require_once 'lib/model.lib.php';

class AdminAccessModel extends Model{

    /**
     * @param IDatabase $db
     */
    public function __construct(IDatabase $db) {
        parent::__construct('admin_access', $db);
        $this->field('user_id', new IntField('user_id'));
        $this->field('route_name', new CharField('route_name'));
    }

    public function getRouteNames($user_id) {
        $filter = new FieldValueSqlFilter();
        $filter->eq('user_id', $user_id);
        $this->get()->filter($filter)->exec();
        return $this;
    }

}
