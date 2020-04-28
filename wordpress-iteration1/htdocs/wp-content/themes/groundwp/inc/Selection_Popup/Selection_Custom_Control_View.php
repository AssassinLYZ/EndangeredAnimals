<div id="<?php echo esc_attr( $this->id ); ?>">
	<span class="customize-control-title">{{label | cap}}</span>
	<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
	<div class="groundwp-sp-customizer-items-wrapper">
		<div class="groundwp-sp-customizer-label"><?php esc_html_e( 'Label', 'groundwp' ); ?></div>
		<div class="groundwp-sp-customizer-label"><?php esc_html_e( 'URL', 'groundwp' ); ?></div>
		<sp-customizer-item inline-template v-for="(item,index) in items">
			<div class="groundwp-sp-customizer-item" @mouseover="showDelete = true" @mouseleave="showDelete=false">
				<input type="text" placeholder="label" v-model="item.title">
				<div class="groundwp-sp-customizer-combo"><input type="text" placeholder="url" v-model="item.url">
					<button :style="{ visibility: showDelete?'visible':'hidden'}" @click="$delete(items, index)"
							class="button groundwp-sp-customizer-button-delete"><span
								class="dashicons dashicons-trash"></span>
					</button>
				</div>
			</div>
		</sp-customizer-item>
		<button class="button groundwp-sp-customizer-button-add" @click.prevent="items.push({title: '', url: ''})"><span class="dashicons dashicons-plus-alt"></span></button>
	</div>
</div>
