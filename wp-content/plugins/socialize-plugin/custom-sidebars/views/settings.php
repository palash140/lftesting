<div class="wrap">

	<h2><?php esc_html_e( 'Sidebars', 'socialize-plugin' ); ?></h2>

	<?php $this->message(); ?>	

	<div id="customsidebarspage">

		<div id="poststuff">
	
			<h3 class="title"><?php esc_html_e( 'New Sidebar', 'socialize-plugin' ); ?></h3>
			
			<p><?php esc_html_e( 'Add your sidebars below and then you can assign one of these sidebars from the individual posts/pages. When a sidebar is created, it is shown on the widgets page where you will be able to configure it.', 'socialize-plugin' ); ?></p>
			
			<form action="themes.php?page=sidebars" method="post">
				
				<?php wp_nonce_field( 'custom-sidebars-new' );?>
				
				<div id="namediv" class="stuffbox">
					
					<h3><label for="sidebar_name"><?php esc_html_e( 'Name', 'socialize-plugin' ); ?></label></h3>
					<div class="inside">
						<input type="text" name="sidebar_name" size="30" tabindex="1" value="" id="link_name" />
						<p><?php esc_html_e( 'This name has to be unique.', 'socialize-plugin' )?></p>
					</div>
	
					<h3><label for="sidebar_description"><?php esc_html_e( 'Description', 'socialize-plugin' ); ?></label></h3>
					<div class="inside">
						<input type="text" name="sidebar_description" size="30" tabindex="1" value="" id="link_url" />
					</div>
					
				</div>
		
				<input type="submit" class="button-primary" name="create-sidebars" value="<?php esc_html_e( 'Create Sidebar', 'socialize-plugin' ); ?>" /><br/><br/>
				
			</form>


			<?php
			////////////////////////////////////////////////////////
			//SIDEBARLIST
			////////////////////////////////////////////////////////////
			?>

			<div id="sidebarslistdiv">
		
				<script type="text/javascript">
					jQuery( document ).ready( function( $ ){
						$( '.csdeletelink' ).click(function(){
							return confirm( "<?php esc_html_e( 'Are you sure to delete this sidebar?', 'socialize-plugin' );?>" );
						});
					});
				</script>
		
				<h3><?php esc_html_e( 'Custom Sidebars', 'socialize-plugin' ); ?></h3>

				<table class="widefat fixed" cellspacing="0">
	
					<thead>
						<tr class="thead">
							<th scope="col" id="name" class="manage-column column-name" style=""><?php esc_html_e( 'Name', 'socialize-plugin' ); ?></th>
							<th scope="col" id="email" class="manage-column column-email" style=""><?php esc_html_e( 'Description', 'socialize-plugin' ); ?></th>
							<th scope="col" id="email" class="manage-column column-email" style=""><?php esc_html_e( 'ID', 'socialize-plugin' ); ?></th>
							<th scope="col" id="config" class="manage-column column-date" style=""></th>
							<th scope="col" id="edit" class="manage-column column-rating" style=""></th>
							<th scope="col" id="delete" class="manage-column column-rating" style=""></th>
						</tr>
					</thead>
				
					<tbody id="custom-sidebars" class="list:user user-list">
	
						<?php if ( sizeof ( $customsidebars ) > 0 ) {
							foreach( $customsidebars as $cs ) { ?>
								<tr id="cs-1" class="alternate">
									<td class="name column-name"><?php echo $cs['name']; ?></td>
									<td class="email column-email"><?php echo $cs['description']; ?></td>
									<td class="email column-email"><?php echo $cs['id']; ?></td>
									<td class="role column-date"><a class="" href="widgets.php"><?php esc_html_e( 'Configure Widgets', 'socialize-plugin' ); ?></a></td>
									<td class="role column-rating"><a class="" href="themes.php?page=sidebars&p=edit&id=<?php echo $cs['id']; ?>"><?php esc_html_e( 'Edit', 'socialize-plugin' ); ?></a></td>
									<td class="role column-rating"><a class="csdeletelink" href="themes.php?page=sidebars&delete=<?php echo $cs['id']; ?>&_n=<?php echo $deletenonce; ?>"><?php esc_html_e( 'Delete', 'socialize-plugin' ); ?></a></td>
								</tr>
							<?php } 
						} else { ?>
							<tr id="cs-1" class="alternate">
								<td colspan="3"><?php esc_html_e( 'There are no custom sidebars available.', 'socialize-plugin' ); ?></td>
							</tr>
						<?php } ?>
		
					</tbody>
	
				</table>
		
			</div><br/>

			<?php
			////////////////////////////////////////////////////////
			//RESET SIDEBARS
			////////////////////////////////////////////////////////////
			?>
	
			<div id="resetsidebarsdiv">
	
				<form action="themes.php?page=sidebars" method="post">
			
					<input type="hidden" name="reset-n" value="<?php echo $deletenonce; ?>" />
			
					<h3><?php esc_html_e( 'Reset Sidebars', 'socialize-plugin' ); ?></h3>
			
					<p><?php esc_html_e( 'Click on the button below to delete all the custom sidebars data from the database. Keep in mind that once the button is clicked you will have to create new sidebars and customize them to restore your current sidebars configuration.', 'socialize-plugin' ); ?></p>
	
					<p class="submit"><input onclick="return confirm('<?php esc_html_e( 'Are you sure to reset the sidebars?', 'socialize-plugin' ); ?>')"type="submit" class="button-primary" name="reset-sidebars" value="<?php esc_html_e( 'Reset Sidebars', 'socialize-plugin' ); ?>" /></p>
	
				</form>
		
			</div>

		</div>
		
	</div>

</div>