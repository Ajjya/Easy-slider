<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

	class DB{
		private $table;
		private $postmeta;

		function __construct() {
			global $wpdb;
			$this->wpdb = $wpdb;
			$this->table = $wpdb->prefix . "easy_slider";
			$this->postmeta = $wpdb->prefix . "postmeta";
		}

		public function is_has_images($post_id){
			$attachments = $this->getAttachmentsIDs($post_id);

			if(count($attachments)){
				return true;
			}

			return false;
		}

		public function getCountImages($post_id){
			$images_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->table WHERE post_id=$post_id"));
			return $images_count;
		}

		public function getAttachmentsIDs($post_id){
			$attachments = $this->wpdb->get_results(
				"SELECT " . $this->table . ".ID 
				FROM " . $this->table . " 
				WHERE " . $this->table . ".post_id = $post_id 
				LIMIT 100"
			);

			$attachments_res = array();
			foreach($attachments as $one_att){
				$attachments_res[] = $one_att->ID;
			}
			return $attachments_res;
		}

		public function getAttachments($post_id){
			$attachments = $this->wpdb->get_results(
				"SELECT " . $this->table . ".ID, "
				 . $this->table . ".post_id, " 
				 . $this->table . ".image_id, "  
				 . $this->table . ".image_order, "
				 . $this->table . ".image_title, "
				 . $this->table . ".image_subtitle, "
				 . $this->table . ".image_signature, "
				 . $this->table . ".image_content, "
				 . $this->postmeta . ".meta_value as meta 
				FROM " . $this->table . " 
				JOIN " . $this->postmeta . " ON " . $this->postmeta . ".post_id = " . $this->table . ".image_id  
				WHERE " . $this->postmeta . ".meta_key = '_wp_attachment_metadata' 
				AND " . $this->table . ".post_id = $post_id 
				ORDER BY " . $this->table . ".image_order ASC 
				LIMIT 100"
			);

			$attachments_res = array();
			foreach($attachments as $key => $one_att){
				$attachments_res[$key]['ID'] = $one_att->ID;
				$attachments_res[$key]['post_id'] = $one_att->post_id;
				$attachments_res[$key]['image_id'] = $one_att->image_id;
				$attachments_res[$key]['image_order'] = $one_att->image_order;
				$attachments_res[$key]['image_title'] = $one_att->image_title;
				$attachments_res[$key]['image_subtitle'] = $one_att->image_subtitle;
				$attachments_res[$key]['image_signature'] = $one_att->image_signature;
				$attachments_res[$key]['image_content'] = $one_att->image_content;
				$attachments_res[$key]['image_src'] = $this->imageDownsize( $one_att->meta );

			}

			return $attachments_res;
		}

		private function imageDownsize( $meta ) {
			$meta = unserialize($meta);
			$url = site_url() . '/wp-content/uploads/' . $meta['file'];
			$pathinfo = pathinfo($url);

			$path = $pathinfo['dirname'];

			foreach ($meta['sizes'] as $key => $value) {
				$meta['sizes'][$key]['file'] = $path . '/' . $meta['sizes'][$key]['file'];
			}

			if(!isset($meta['sizes']['large'])){
				$meta['sizes']['large'] = array(
					"file" => $url,
					"width" => $meta['width'],
					"height" => $meta['height']
				); 
			}

			if(!isset($meta['sizes']['medium_large'])){
				$meta['sizes']['medium_large'] = array(
					"file" => $url,
					"width" => $meta['width'],
					"height" => $meta['height']
				); 
			}

			if(!isset($meta['sizes']['slider-big'])){
				$meta['sizes']['slider-big'] = array(
					"file" => $url,
					"width" => $meta['width'],
					"height" => $meta['height']
				); 
			}

			if(!isset($meta['sizes']['slider-biggest'])){
				$meta['sizes']['slider-biggest'] = array(
					"file" => $url,
					"width" => $meta['width'],
					"height" => $meta['height']
				); 
			}


			return array(
				'url' => site_url() . '/wp-content/uploads/' . $meta['file'],
				'sizes' => $meta['sizes']
			);
		}

		private function escapeString($str){
			$str = str_replace('<', '&lt;', $str);
			$str = str_replace('>', '&gt;', $str);
			return $str;
		}

		public function cleanAttachments($post_id){
			$this->wpdb->delete( $this->table, array('post_id' => $post_id), array( '%d' ) );
		}

		public function removeAttachments($ids){
			$ids = implode(' ,', $ids);
			$this->wpdb->query( "DELETE FROM " . $this->table . " WHERE ID IN($ids)" );
		}

		public function editAttachments($data){
			$created_date = new DateTime();
			$created_date = $created_date->format('Y-m-d H:i:s');

			$this->wpdb->update( 
				$this->table, 
				array(
					'updated_at' => $created_date,
					'image_id' => $data['image_id'],
					'post_id' => $data['post_id'],
	                'image_order' => $data['image_order'],
	                'image_title' => $this->escapeString($data['image_title']),
	                'image_subtitle' => $this->escapeString($data['image_subtitle']),
	                'image_signature' => $this->escapeString($data['image_signature']),
	                'image_content' => $this->escapeString($data['image_content'])
				), 
				array(
					'ID' => $data['ID']
				),
				array(
					'%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s'
				),
				array(
					'%d'
				)
			);

			return $data['ID'];
		}

		public function addAttachments($data){
			$created_date = new DateTime();
			$created_date = $created_date->format('Y-m-d H:i:s');

			$this->wpdb->insert( 
				$this->table, 
				array(
					'created_at' => $created_date,
					'updated_at' => $created_date,
					'post_id' => $data['post_id'],
					'image_id' => $data['image_id'],
	                'image_order' => $data['image_order'],
	                'image_title' => $this->escapeString($data['image_title']),
	                'image_subtitle' => $this->escapeString($data['image_subtitle']),
	                'image_signature' => $this->escapeString($data['image_signature']),
	                'image_content' => $this->escapeString($data['image_content'])
				), 
				array(
					'%s', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s'
				)
			);

			$lastid = $this->wpdb->insert_id;
			return $lastid;
		}

	}