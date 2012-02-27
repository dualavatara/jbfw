<?php
/**
 * User: zhukov
 * Date: 27.02.12
 * Time: 15:27
 */

define("MODEL_ID_FIELD_NAME", "id");

interface ISqlCmd {
	public function sql(Model $model);
	public function exec(Model $model, $async = false);
}
