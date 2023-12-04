<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Updates;
use App\Models\Messages;
use App\Models\AdminSettings;
use App\Models\Media;
use Carbon\Carbon;
use App\Helper;
use Image;
use FileUploader;

class UploadMediaFileShopController extends Controller
{

	public function __construct(AdminSettings $settings, Request $request)
  {
		$this->settings = $settings::first();
		$this->request = $request;
    $this->middleware('auth');
	}

	/**
     * submit the form
     *
     * @return void
     */
	public function store()
	{
		$publicPath = public_path('temp/');
		$file = strtolower(auth()->id().uniqid().time().str_random(20));

		// initialize FileUploader
		$FileUploader = new FileUploader('file', array(
			'limit' => 1,
			'fileMaxSize' => floor($this->settings->file_size_allowed / 1024),
			'extensions' => [
				'png',
				'jpeg',
				'jpg',
				'gif',
				'ief',
				'video/mp4',
				'video/quicktime',
				'video/3gpp',
				'video/mpeg',
				'video/x-matroska',
				'video/x-ms-wmv',
				'video/vnd.avi',
				'video/avi',
				'video/x-flv',
				'audio/x-matroska',
				'audio/mpeg',
				'application/x-zip-compressed',
				'application/zip',
				'application/pdf'
	    ],
			'title' => $file,
			'uploadDir' => $publicPath
		));

		// upload
		$upload = $FileUploader->upload();

		if ($upload['isSuccess']) {
			foreach($upload['files'] as $key=>$item) {
				$upload['files'][$key] = [
					'extension' => $item['extension'],
					'format' => $item['format'],
					'name' => $item['name'],
					'size' => $item['size'],
					'size2' => $item['size2'],
					'type' => $item['type'],
					'uploaded' => true,
					'replaced' => false
				];

			}// foreach

		}// upload isSuccess

		return response()->json($upload);
	}

	/**
     * delete a file
     *
     * @return void
     */
	public function delete()
	{
		// PATH
		$local = 'temp/';

		// Delete local file
		Storage::disk('default')->delete($local.$this->request->file);
		
		return response()->json([
        'success' => true
	 ]);
	}// End method

}
