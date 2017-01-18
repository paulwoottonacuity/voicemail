<div class="mailbox">
	<div class="row">
		<div class="col-md-3">
			<div class="folder-list">
			<?php foreach($folders as $f) {?>
				<div class="folder <?php echo ($f['folder'] == $folder) ? 'active' : ''?>" data-name="<?php echo $f['name']?>" data-folder="<?php echo $f['folder']?>"><a href="#" class="folder-inner"><?php echo $f['name']?> <span class="badge"><?php echo isset($f['count']) ? $f['count'] : 0?></span></a></div>
			<?php }?>
			</div>
		</div>
		<div class="col-md-9">
			<?php if(!empty($message)) { ?>
				<div class="alert alert-<?php echo $message['type']?>"><?php echo $message['message']?></div>
			<?php } ?>
			<?php if($settings['options']['delete'] == "yes") {?>
				<div class="alert alert-warning"><?php echo _("Voicemail Auto Delete is on. New messages will not show up here.")?></div>
			<?php } ?>
			<div id="voicemail-toolbar">
				<button id="delete-selection" class="btn btn-danger" disabled>
					<i class="glyphicon glyphicon-remove"></i> <span><?php echo _('Delete')?></span>
				</button>
				<button id="forward-selection" class="btn btn-default" disabled>
					<i class="fa fa-share"></i> <span><?php echo _('Forward')?></span>
				</button>
				<button id="move-selection" class="btn btn-default" disabled>
					<i class="fa fa-arrows"></i> <span><?php echo _('Move')?></span>
				</button>
			</div>
			<table class="voicemail-grid"
				data-url="index.php?quietmode=1&amp;module=voicemail&amp;command=grid&amp;folder=<?php echo htmlentities($folder)?>&amp;ext=<?php echo htmlentities($ext)?>"
				data-cache="false"
				data-toolbar="#voicemail-toolbar"
				data-cookie="true"
				data-cookie-id-table="ucp-voicemail-table-<?php echo $folder?>"
				data-maintain-selected="true"
				data-show-columns="true"
				data-show-toggle="true"
				data-toggle="table"
				data-sort-order="desc"
				data-sort-name="origtime"
				data-pagination="true"
				data-side-pagination="server"
				data-unique-id="msg_id"
				data-show-refresh="true"
				data-silent-sort="false"
				data-mobile-responsive="true"
				data-check-on-init="true"
				class="table table-hover">
				<thead>
					<tr class="message-header">
						<th data-checkbox="true"></th>
						<th data-field="origtime" data-sortable="true" data-formatter="UCP.Modules.Voicemail.dateFormatter"><?php echo _("Date")?></th>
						<th data-field="callerid" data-sortable="true"><?php echo _("CID")?></th>
						<?php if($showPlayback) { ?>
							<th data-field="playback" data-formatter="UCP.Modules.Voicemail.playbackFormatter"><?php echo _("Playback")?></th>
						<?php } ?>
						<th data-field="duration" data-sortable="true" data-formatter="UCP.Modules.Voicemail.durationFormatter"><?php echo _("Duration")?></th>
						<th data-field="controls" data-formatter="UCP.Modules.Voicemail.controlFormatter"><?php echo _("Controls")?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>