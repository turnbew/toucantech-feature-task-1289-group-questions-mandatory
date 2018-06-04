FOR PRIVACY AND CODE PROTECTING REASONS THIS IS A SIMPLIFIED VERSION OF CHANGES AND NEW FEATURES

TASK DATE: 12.04.2018 - FINISHED: 16.04.2018

TASK LEVEL: MEDIUM (light)

TASK SHORT DESCRIPTION: ["Be able to make user group questions mandatory or not.
	'Required' tickbox option in custom field edit pop up. Default = 0. If ticked (1), 
	make question required on registration form."]

GITHUB REPOSITORY CODE: feature/task-1289-group-questions-mandatory
	
CHANGES
 
	NEW FILES 
	
		group_questions_table.php
		group_questions_table_header.php
		group_questions_table_row.php
		group_questions_table_row_add_field.php
		user_groups_lang.php
 
	IN FILES: 
		
		members.php
		
			TOTALLY changed group_questions function
			
				/**
				 * group questions
				 *
				 * get all questions for a group
				 */
				public function group_questions() 
				{
					$data=array();
					
					if(!$this->input->is_ajax_request() or !$this->current_user)  {
						show_404();
					}
					
					//Loading some stuffs
					$this->load->model('network_settings_m');
					$this->lang->load('user_groups');
					
					....................		

					//send response
					$response = array(
						'status' => 'ok',
						'html' => $this->load->view('...........group_questions', $data, true),
					);

					$this->template->build_json($response);
				} 
			
			
			ADDED functions: 
			
				//Getting table rows for group questions
				protected function _getTableBody($records, $personTypeId, $institutionName, $questionType = '')
				{	
					$output = "";
					foreach ($records as $question) 
					{
						...........
					}
					return $output;
				}//END inside function _getTableBody	
				
			

				public function ajaxUpdateQuestionMandatory() 
				{
					if( ! $this->input->is_ajax_request())  {
						show_404(); exit;
					}

					....................
					
					$result = false;
					if ($questionId > 0 and $personTypeId > 0) {
						$result = $this->db->set('is_required', $value)
										->where('question_id', $questionId)
										->where('person_type_id', $personTypeId)
										->update($this->db->dbprefix('group_questions'));
					}

					echo $result;
				}//END ajaxUpdateQuestionMandatory 
				
				
				
				public function ajaxGetGroupQuestion() 
				{
					//checking was an AJAX call
					if( ! $this->input->is_ajax_request())  {
						show_404(); exit;
					}
					
					.............
					
					//send back the table-row
					echo $this->_getTableBody($question, $personTypeId, $institutionName, 'custom');
					
				}//END ajaxGetGroupQuestion 	
			
		
		
		user_groups.php
		
			ADDED Modals
			
			<!-- Add Custom Question Modal -->
			<div class="modal fade" id="custom-add-question-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

			<!-- Custom Question's Options Modal -->
			<div class="modal fade" id="question-options-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


			
		
		custom_questions_dialog.php
		
			ADDED mandatory section
			
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<input class="action-set-question-mandatory" data-question-id="<?=$question->question_id?>" data-person-type-id="<?=$person_type_id?>" type="checkbox" <? if($question->is_required) echo 'CHECKED'?>>
						</span>
						<input style="color: #000; background-color: #fff;" type="text" class="form-control" value="<?=lang('user_groups:label:checkbox_mandatory')?>" disabled="disabled">
					</div>
				</div>
		
		
			ADDED Delete button: 
			
				<? if ($deletable) : ?>			
					<button type="button" class="btn btn-danger" id="delete_custom_question" data-person-type-id="<?=$question->person_type_id?>" data-question-id="<?=$question->question_id?>">Delete</button>
				<? endif; ?>	
		
		
		user_groups.css
		
			ADDED CODE:
			
				table.group-questions2 {
					width: 100%;
					border: solid 1px #e3e3e3;
					margin-bottom: 20px;
				}

				table.group-questions2 th,
				table.group-questions2 tr.question-line td {
					font-weight: normal;
					text-transform: none;
					font-size: 13px;
					text-align: center;
					vertical-align: center;
					color: black;
					border: solid 1px #e3e3e3;
					padding: 4px;
				}

				table.group-questions2 th:first-child {
					padding-left: 20px;
					text-align: left;
				}

				table.group-questions2 th.grey-bg,
				table.group-questions2 tr.question-line td:nth-child(3),
				table.group-questions2 tr.question-line td:nth-child(5) {
					background:  #efefef;
				}

				table.group-questions2 tbody tr.question-line:hover td {
					background:  #d3d3d3 !important;
				}

				table.group-questions2 tr.question-line td:nth-child(1) {
					text-align: right;
					vertical-align: top;
					color: #900;
					font-size: 13px;
					font-weight: bold;
					width: 10px;
				}

				table.group-questions2 tr.question-line td:nth-child(1) p {
					height: 20px;
					margin: 0px;
					padding: 0px;
				}

				table.group-questions2 tr.question-line td {
					padding: 5px;
					font-size: 12px;
					border: none;
					min-height: 50px !Important;
				}

				table.group-questions2 tr.question-line td:nth-child(2) {
					padding-left: 0px;
					text-align: left;
				}

				table.group-questions2 tr.question-line td a {
					text-decoration: none;
					color: #999;
					cursor: pointer;
				}
		
		
		
		user_groups.js
		
			ADDED NEW Controll parts
			
				//save mandatory field changes
				$('.action-set-question-mandatory').live("change", function(event)
				{
					//avoiding multiple firing
					event.preventDefault();
					
					//grabbing some necessary data
					var $this = $(this);
					var value = $this.prop('checked') ? 1 : 0;
					var questionId = $this.data("question-id");
					var personTypeId = $this.data("person-type-id");

					//passing data to the controller, set changes in DB
					AJAX.call('..........ajaxUpdateQuestionMandatory', {'value' : value, 'questionId' : questionId, 'personTypeId' : personTypeId}, function(response) {
						...........
					})
				});
		
		
				//Add a custom question - From custom questions.js file
				$("#btnAddQuestion").live('click', function (event) 
				{	
					//avoiding multiple firing
					event.preventDefault();		
					
					//set some vars
					var $this = $(this);
					var personTypeId = $this.data('person-type-id');
							
					//making modal empty (clear cache)
					$('.modal-content').empty();

					//getting modal
					$('#custom-add-question-modal')
						.removeData('bs.modal')
						.modal({
							remote: '..........custom_questions_dialog/0/' + personTypeId,
						})
				});

				
				//Saving added custom question
				$('#save-custom-question').live('click', function (event)
				{
					//avoiding multiple firing
					event.preventDefault();			

					//init
					var $this = $(this);
					var btnSaveSelector = '#' + $this.prop('id');
					var btnCancelSelector = '#cancel-custom-question';
					var btnAddSelector = '#add-custom-question-option';
					
					//disabling buttons
					COMMON.disableButton(btnSaveSelector);
					COMMON.disableButton(btnCancelSelector);
					COMMON.disableButton(btnAddSelector);
					
					//action - 1. AJAX call: saving new question into DB - 2. AJAX call: getting new HTML snippet for the new question
					AJAX.call('............save_custom_question/', $('#custom-questions-options form').serialize(), function(response){
						var result = $.parseJSON(response);
						if( result.status == 'success' ) {
							var personTypeId = $('#add_question_person_type_id').val(); 
							if (personTypeId > 0) {
								AJAX.call('............ajaxGetGroupQuestion', {'questionId' : result.question_id, 'personTypeId' : personTypeId}, function(tableRow) {	
									$('#group_questions2_body_custom_' + personTypeId).append( tableRow );
									COMMON.enableButton(btnSaveSelector, 'btn-primary');
									COMMON.enableButton(btnCancelSelector, 'btn-primary');
									COMMON.enableButton(btnAddSelector, 'btn-primary');
									$('#custom-add-question-modal').modal('hide');
								});
							}
						} else {
							alert('sorry we got an error, please try again');
							COMMON.enableButton(btnSaveSelector, 'btn-primary');
							COMMON.enableButton(btnCancelSelector, 'btn-primary');
							COMMON.enableButton(btnAddSelector, 'btn-primary');
						}
					})
				});

				
				//deleting custom questions
				$('#delete_custom_question').live('click', function (event)
				{
					//avoiding multiple firing 
					event.preventDefault();	
					
					//init - set some vars 
					var $this = $(this);
					var questionId = $this.data('question-id');
					var personTypeId = $this.data('person-type-id');
					var btnDeleteSelector = '#delete_custom_question';
					var btnSaveSelector = '#save_questions_details';
					
					//disabling buttons
					COMMON.disableButton(btnSaveSelector);
					COMMON.disableButton(btnDeleteSelector);
					
					//execute deleting
					$.confirm({
						text: "Are you sure you want to delete this question?",
						confirm: function() {
							if (questionId > 0 && personTypeId > 0) {
								AJAX.call('..........delete_custom_question/' + questionId, {}, function(response) {
									var result = $.parseJSON(response);
									..................
								});
							}
						},
						cancel: function() {				  
							COMMON.enableButton(btnSaveSelector, 'btn-primary');
							COMMON.enableButton(btnDeleteSelector, 'btn-primary');
						}
					});
				});
		
		
		
		
		profile_questions_m.php
		
			Inside functions: 
				- get_registration_questions
				- get_enabled_group_questions
				- get_group_question
				
					ADD new fields for the queries: g.is_required,
			
			EVERY FILE INSIDE THIS FOLDER WAS UPDATED ...
			
			Set required fields for Multi-select options field: 
				, 'class' => $question->is_required ? 'required' : ''
			.....
			Set required fields for drop_down fields
				' . ($question->is_required ? "required " : "") . '
			.....
			Set required fields for input and textarea fields
				<?=($question->is_required ? "required " : "")?>
			
			
			
		details.php
		
			Inside upgrade function 
			
				 if(version_compare($old_version, '2.0.19', 'lt')){
				   $this->db->add_boolean_field_to_table($table = $this->db->dbprefix("group_questions"), $field = "is_required", $null = false, $default = false, $after = '');
				   $this->_udpdateGroupQuestionsTable();
				   $this->_cleanProfileQuestionsTable();
				}
				
			Inside install function 
			
				$this->db->add_boolean_field_to_table($table = $this->db->dbprefix("group_questions"), $field = "is_required", $null = false, $default = false, $after = '');
				$this->_udpdateGroupQuestionsTable();
				
				
			ADDED NEW FUNCTION 
			
				private function _udpdateGroupQuestionsTable() 
				{
					#first we catch the id of question whose type_slug is years-from-to
					.............
				}//END function _udpdateGroupQuestionsTable
				
				
				/*
				 * NOT SURE THIS IS NECESSARY, UNDER DISCUSSON
				*/
				private function _cleanProfileQuestionsTable()
				{
					$deletableFields = array('free_text_1', 'free_text_2', 'free_text_3', 'free_text_4', 'free_text_5');	
					
					foreach($deletableFields as $key => $field) 
					{
						#first we check belongs data to the field or doesn't 
						...................
						
						#delete field if there's no data which belong to the field
						if ($hitsOffline == 0 and $hits == 0) {
							#delete question from group_questions_table
							$typeSlug = str_replace("_", "-", $field);
							
						}
					}
				}//END function _cleanProfileQuestionsTable
