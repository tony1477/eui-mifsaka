<?php
class WidgetController extends Controller {
	public $menuname = 'widget';
	public function actionIndex() {
		parent::actionIndex();
		if (isset($_GET['grid'])) 
			echo $this->search();
		else 
			$this->renderPartial('index', array());
	}
	public function search() {
		header("Content-Type: application/json");
		$widgetid			 = isset($_POST['widgetid']) ? $_POST['widgetid'] : '';
		$widgetname		 = isset($_POST['widgetname']) ? $_POST['widgetname'] : '';
		$widgettitle		 = isset($_POST['widgettitle']) ? $_POST['widgettitle'] : '';
		$recordstatus	 = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$widgetid			 = isset($_GET['q']) ? $_GET['q'] : $widgetid;
		$widgetname		 = isset($_GET['q']) ? $_GET['q'] : $widgetname;
		$widgettitle		 = isset($_GET['q']) ? $_GET['q'] : $widgettitle;
		$recordstatus	 = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page		 = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows		 = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort		 = isset($_POST['sort']) ? strval($_POST['sort']) : 'widgetid';
		$order	 = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset	 = ($page - 1) * $rows;
		$page		 = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows		 = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort		 = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0)
				? $sort : 't.'.$sort;
		$order	 = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset	 = ($page - 1) * $rows;
		$result	 = array();
		$row		 = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) and (widgetname like :widgetname) and (widgettitle like :widgettitle)',
					array(':widgetid' => '%'.$widgetid.'%', ':widgetname' => '%'.$widgetname.'%', ':widgettitle' => '%'.$widgettitle.'%'))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) or (widgetname like :widgetname) or (widgettitle like :widgettitle)',
					array(':widgetid' => '%'.$widgetid.'%', ':widgetname' => '%'.$widgetname.'%', ':widgettitle' => '%'.$widgettitle.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('*')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) and (widgetname like :widgetname) and (widgettitle like :widgettitle)',
					array(':widgetid' => '%'.$widgetid.'%', ':widgetname' => '%'.$widgetname.'%', ':widgettitle' => '%'.$widgettitle.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('*')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) or (widgetname like :widgetname) or (widgettitle like :widgettitle)',
					array(':widgetid' => '%'.$widgetid.'%', ':widgetname' => '%'.$widgetname.'%', ':widgettitle' => '%'.$widgettitle.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'widgetid' => $data['widgetid'],
				'widgetname' => $data['widgetname'],
				'widgettitle' => $data['widgettitle'],
				'widgetversion' => $data['widgetversion'],
				'widgetby' => $data['widgetby'],
				'description' => $data['description'],
				'widgeturl' => $data['widgeturl'],
				'modulename' => $data['modulename'],
				'moduleid' => $data['moduleid'],
				'recordstatus' => $data['recordstatus'],
			);
		}
		$result = array_merge($result, array('rows' => $row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql		 = 'call Insertwidget(:vwidgetname,:vwidgettitle,:vwidgetversion,:vwidgetby,:vdescription,:vwidgeturl,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql		 = 'call Updatewidget(:vid,:vwidgetname,:vwidgettitle,:vwidgetversion,:vwidgetby,:vdescription,:vwidgeturl,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vwidgetname', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vwidgettitle', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vwidgetversion', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vwidgetby', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vwidgeturl', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-widget"]["name"]);
		if (move_uploaded_file($_FILES["file-widget"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$widgetname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$widgettitle = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$widgetversion = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$widgetby = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$widgeturl = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$modulename = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$widgetname,$widgettitle,$widgetversion,
						$widgetby,$description,$widgeturl,$moduleid,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false, 'insertsuccess');
			} catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['widgetid'])?$_POST['widgetid']:''),$_POST['widgetname'],$_POST['widgettitle'],$_POST['widgetversion'],
				$_POST['widgetby'],$_POST['description'],$_POST['widgeturl'],$_POST['moduleid'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false, 'insertsuccess');
		} catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id					 = $_POST['id'];
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				$sql		 = 'call Purgewidget(:vid,:vdatauser)';
				$command = $connection->createCommand($sql);
				foreach ($id as $ids) {
					$command->bindvalue(':vid', $ids, PDO::PARAM_STR);
					$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				GetMessage(false, 'insertsuccess');
			} catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true, $e->getMessage());
			}
		} else {
			GetMessage(true, 'chooseone');
		}
	}
	public function actionDownPDF() {
		parent::actionDownload();
		$sql = "select widgetid,widgetname,widgettitle,recordstatus
				from widget a ";
		$widgetid = filter_input(INPUT_GET,'widgetid');
		$widgetname = filter_input(INPUT_GET,'widgetname');
		$widgettitle = filter_input(INPUT_GET,'widgettitle');
		$sql .= " where coalesce(a.widgetid,'') like '%".$widgetid."%' 
			and coalesce(a.widgetname,'') like '%".$widgetname."%'
			and coalesce(a.widgettitle,'') like '%".$widgettitle."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql." and a.widgetid in (".$_GET['id'].")";
		}
		$command		 = $this->connection->createCommand($sql);
		$dataReader	 = $command->queryAll();
		$this->pdf->title					 = GetCatalog('widget');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('L', 'L', 'L');
		$this->pdf->colheader			 = array(GetCatalog('widgetname'),GetCatalog('widgettitle'),
			GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(40, 80, 40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['widgetname'], $row1['widgettitle'], $row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='widget';
		parent::actionDownxls();
		$sql = "select widgetid,widgetname,recordstatus
				from widget a ";
		$widgetid = filter_input(INPUT_GET,'widgetid');
		$widgetname = filter_input(INPUT_GET,'widgetname');
		$sql .= " where coalesce(a.widgetid,'') like '%".$widgetid."%' 
			and coalesce(a.widgetname,'') like '%".$widgetname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql." and a.widgetid in (".$_GET['id'].")";
		}
		$command		 = $this->connection->createCommand($sql);
		$dataReader	 = $command->queryAll();
		$i					 = 1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, 1, GetCatalog('widgetname'))
			->setCellValueByColumnAndRow(1, 1, GetCatalog('widgettitle'))
			->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
		foreach ($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i + 1, $row1['widgetname'])
				->setCellValueByColumnAndRow(1, $i + 1, $row1['widgettitle'])
				->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
			$i += 1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}