			<tr class="question-line" id="question_row_<?=$questionType?>_<?=$question->person_type_id?>_<?=$question->question_id?>">
				<td><p id="mandatory_<?=$personTypeId?>_<?=$question->question_id?>"><?=$question->requiredText?></p></td>
				<td><?=$question->questionText?></td>
				<td>
					<input type="checkbox" data-group-question-type="question-enabled" data-person-type-id="<?=$personTypeId?>" data-group-question-id="<?=$question->group_question_id?>" id="group_question_enabled_<?=$question->group_question_id?>" name="group_question_enabled_<?=$question->group_question_id?>"<?=$question->enabledChecked?>>
				</td>
				<td>
					<? if($question->enabled_on_registration):?>
						<input type="checkbox" data-group-question-type="question-ask-on-reg" data-person-type-id="<?=$personTypeId?>" data-group-question-id="<?=$question->group_question_id?>" id="group_question_reg_<?=$question->group_question_id?>" name="group_question_reg_<?=$question->group_question_id?>"<?=$question->askOnRegistrationChecked?>></td>
					<? else: ?>
						<span class="glyphicon glyphicon-ban-circle"></span>
					<? endif; ?>
				<td>
					<input type="checkbox" data-group-question-type="question-offline" data-person-type-id="<?=$personTypeId?>" data-group-question-id="<?=$question->group_question_id?>" id="group_question_offline_<?=$question->group_question_id?>"  name="group_question_offline_<?=$question->group_question_id?>"<?=$question->offlineUseChecked?>>
				</td>
				<td>
					<a id="edit-<?=$question->group_question_id?>" data-group-question-id="<?=$question->group_question_id?>" data-person-type-id="<?=$personTypeId?>" class="glyphicon glyphicon-pencil edit-question-details"></a>
				</td>
			</tr>
