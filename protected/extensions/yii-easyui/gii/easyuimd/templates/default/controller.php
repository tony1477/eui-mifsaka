<?php $columns = '';$ncolumns = '';$pcolumns = '';$ppcolumns='';$aligncol='';$widthcolumn='';
echo "<?php\n"; ?>
class <?php echo $this->controllerClass; ?> extends Controller
{
	public $menuname = '<?php echo strtolower($this->modelClass); ?>';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function actionSave()
	{
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			<?php 
						foreach($this->tableSchema->columns as $column)
						{
							if($column->autoIncrement)
								continue;
							if ($columns == '') 
							{
								$columns = ':v'.$column->name;
								$ncolumns = $column->name;
								$pcolumns = "\$row1['".$column->name."']";
								$ppcolumns = "Catalogsys::model()->getcatalog('".$column->name."')";
								$aligncol = "'L'";
								$widthcolumn = "40";
							}
							else
							{
								$columns .= ',:v'.$column->name;
								$ncolumns .= ','.$column->name;
								$pcolumns .= ",\$row1['".$column->name."']";
								$ppcolumns .= ",\nCatalogsys::model()->getcatalog('".$column->name."')";
								$aligncol .= ",'L'";
								$widthcolumn .= ",40";
							}
						}
						$columns .= ',:vcreatedby';
						$ncolumns .= "\n";
					?>
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insert<?php echo strtolower($this->modelClass); ?>(<?php echo $columns ?>)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Update<?php echo strtolower($this->modelClass); ?>(:vid,<?php echo $columns ?>)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['<?php echo $this->getModelID()?>'],PDO::PARAM_STR);
			}
			<?php foreach($this->tableSchema->columns as $column)
						{
							if($column->autoIncrement)
							{
								continue;
							}
							else
							if (stripos($column->dbType,'date')!==false)
							{
								echo "\$command->bindvalue(':v".$column->name."',date(Yii::app()->params['datetodb'], strtotime(\$_POST['".$column->name."'])),PDO::PARAM_STR);\n";
							}
							else
							{
								echo "\$command->bindvalue(':v".$column->name."',\$_POST['".$column->name."'],PDO::PARAM_STR);\n";
							}
						}
						?>
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			$this->DeleteLock($this->menuname, $_POST['<?php echo $this->getModelID()?>']);
			$this->GetMessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			$this->GetMessage(true,$e->getMessage());
		}
	}
	
	public function actionPurge()
	{
		header("Content-Type: application/json");
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purge<?php echo $this->controller ?>(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				$this->GetMessage(false,'insertsuccess');
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				$this->GetMessage(true,$e->getMessage());
			}
		}
		else
		{
			$this->GetMessage(true,'chooseone');
		}
	}
	
	public function search()
	{
		header("Content-Type: application/json");
		
		// search 
		<?php foreach($this->tableSchema->columns as $column)
		{
			echo "\$".$column->name." = isset (\$_POST['".$column->name."']) ? \$_POST['".$column->name."'] : '';\n";
		}
		?>
		<?php foreach($this->tableSchema->columns as $column)
		{
			echo "\$".$column->name." = isset (\$_GET['q']) ? \$_GET['q'] : \$".$column->name.";\n";
		}
		?>
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.<?php echo $this->getmodelID() ?>';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		$criteria = new CDbCriteria;
		<?php foreach($this->tableSchema->columns as $column)
		{
			echo "\$criteria->compare('t.".$column->name."',\$".$column->name.",true,'or');\n";
		}
		?>
	
		$result['total'] = count(<?php echo $this->modelClass ?>::model()->findAll($criteria));
		
		$criteria->offset=$offset;
		$criteria->limit=$rows;
		$criteria->order=$sort.' '.$order;
		
		foreach(<?php echo $this->modelClass ?>::model()->findAll($criteria) as $data)
		{	
			$row[] = array(
		<?php foreach($this->tableSchema->columns as $column)
		{
			echo "'".$column->name."'=>\$data->".$column->name.",\n";
		}
		?>
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select <?php echo $ncolumns ?>
				from <?php echo strtolower($this->controller) ?> a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.<?php echo $this->getModelID() ?> in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=<?php echo "Catalogsys::model()->getcatalog('".$this->controller."')" ?>;
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array(<?php echo $aligncol ?>);
		//masukkan colom judul
		$this->pdf->colheader = array(<?php echo $ppcolumns ?>);
		$this->pdf->setwidths(array(<?php echo $widthcolumn ?>));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array(<?php echo $aligncol ?>);
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array(<?php echo $pcolumns ?>));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select <?php echo $ncolumns ?>
				from <?php echo strtolower($this->controller) ?> a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.<?php echo $this->getModelID() ?> in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		<?php 
		$i=0;
		foreach($this->tableSchema->columns as $column)
		{
			if($column->autoIncrement)
							{
								continue;
							}
							else
							{
			echo "->setCellValueByColumnAndRow(".$i.",1,Catalogsys::model()->getcatalog('".$column->name."'))\n";
			$i+=1;
							}
		}
		echo ";";
		?>
		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				<?php 
		$i=0;
		foreach($this->tableSchema->columns as $column)
		{
			if($column->autoIncrement)
							{
								continue;
							}
							else
							{
			echo "->setCellValueByColumnAndRow(".$i.", \$i+1, \$row1['".$column->name."'])\n";
			$i+=1;
							}
		}
		echo ";";
		?>
		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="<?php echo $this->controller ?>.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);
	}
	

}
