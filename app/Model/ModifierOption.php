<?php
App::uses('AppModel', 'Model');
class ModifierOption extends AppModel {
	public $actsAs = array('Common');

	public $belongsTo = array(
		'Modifier'=>array(
			'className' => 'Modifier',
			'foreignKey' => 'modifier_id',
			'dependent' => false
		),
		'Option'=>array(
			'className' => 'Option',
			'foreignKey' => 'option_id',
			'dependent' => false
		)
	);
	
	public function findAllData($modifierId){
		$allData=$this->find('all',array('conditions'=>array('modifier_id'=>$modifierId)));
		if(!empty($allData)){
			foreach($allData as $data){
				$datas[$data['ModifierOption']['option_id']]=$data;
			}
		}
		return $datas;
	}
	
}