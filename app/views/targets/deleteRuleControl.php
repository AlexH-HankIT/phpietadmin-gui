<div class="container">
	<div class='panel panel-default'>
		<ol class='panel-heading breadcrumb'>
			<li><a href='#'>Targets</a></li>
			<li><a href='#'>Configure</a></li>
			<li class='active'>Delete ACL</li>
		</ol>

		<div class='panel-body'>
			<button id="deleteRuleButton" class="btn btn-danger" type="submit"><span
					class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
			</button>

			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-default active">
					<input type="radio" name="ruleType" value="initiators" checked="checked">Initiators
				</label>

				<label class="btn btn-default">
					<input type="radio" name="ruleType" value="targets"> Targets
				</label>
			</div>
		</div>
	</div>
</div>

<div id="deleteRuleData"></div>

<script>
	require(['common'], function () {
		require(['pages/target/deleteRuleControl', 'domReady'], function (methods, domReady) {
			domReady(function () {
				methods.loadData();
				methods.addEventHandler();
			});
		});
	});
</script>