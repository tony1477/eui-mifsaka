<?php

Yii::import('gii.generators.crud.CrudCode');

class easyuiCode extends CrudCode
{
	public function getFormEasyui($modelClass, $column)
	{
		if (preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
			$inputField='password';
		else
			$inputField='text';
		
		
		if (!$column->allowNull)
			$required = ' required="true"';
		else
			$required = '';

		if ($column->type!=='string' || $column->size===null)
			return "<input type=\"$inputField\" name=\"$column->name\" class=\"easyui-validatebox easyui-numberspinner\"$required size=\"53\" />";
		else
			return "<input type=\"$inputField\" name=\"$column->name\" class=\"easyui-validatebox\"$required maxlength=\"$column->size\" size=\"53\" />";
	}
	
	public function getHeight($column)
	{
		$jml = count($column);
		$tot = (int)$jml * 60;
		if($tot > 400)
			return 400;
		else
			return $tot;
	}
	
	public function getModelID()
	{
		foreach($this->tableSchema->columns as $column)
		{
			if ($column->autoIncrement)
			{
				return $column->name;
			}
		}
	}
}
