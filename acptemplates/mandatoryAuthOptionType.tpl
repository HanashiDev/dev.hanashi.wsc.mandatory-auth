<select id="{$option->optionName}" name="values[{$option->optionName}]">
	<option>{lang}wcf.global.noSelection{/lang}</option>
	{foreach from=$authNames item=authName}
		<option value="{$authName}"{if $authName == $value} selected{/if}>
			{$authName}
		</option>
	{/foreach}
</select>
