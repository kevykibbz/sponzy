<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Reports;
use App\Models\Products;
use App\Models\TaxRates;
use App\Models\Purchases;
use Illuminate\Http\File;
use App\Models\Withdrawals;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\MediaProducts;
use App\Models\Notifications;
use App\Models\ShopCategories;
use App\Notifications\NewSale;
use Illuminate\Validation\Rule;
use App\Models\ReferralTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
  use Traits\Functions;

  public function __construct(AdminSettings $settings, Request $request)
  {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  public function index()
  {
    if (!$this->settings->shop) {
      abort(404);
    }

    $tags = request('tags');
    $sort = request('sort');
    $cat  = request('cat');
    $category = null;

    $products = Products::with('seller:name,username,avatar')->whereStatus('1');
    $categories = ShopCategories::orderBy('name')->get();

    if ($cat) {
      $category = ShopCategories::whereSlug($cat)->firstOrFail();
    }

    // Filter by Category
    $products->when($cat, function ($q) use ($cat, $category) {
      $q->where('category', $category->id);
    });

    // Filter by tags
    $products->when(strlen($tags) > 2, function ($q) use ($tags) {
      $q->where('tags', 'LIKE', '%' . $tags . '%');
    });

    // Filter by oldest
    $products->when($sort == 'oldest', function ($q) {
      $q->orderBy('id', 'asc');
    });

    // Filter by lowest price
    $products->when($sort == 'priceMin', function ($q) {
      $q->orderBy('price', 'asc');
    });

    // Filter by Highest price
    $products->when($sort == 'priceMax', function ($q) {
      $q->orderBy('price', 'desc');
    });

    // Filter by Physical Products
    $products->when($sort == 'physical', function ($q) {
      $q->where('type', 'physical');
    });

    // Filter by Digital Products
    $products->when($sort == 'digital', function ($q) {
      $q->where('type', 'digital');
    });

    // Filter by Custom Content
    $products->when($sort == 'custom', function ($q) {
      $q->where('type', 'custom');
    });

    $products = $products->orderBy('id', 'desc')
      ->paginate(15);

    return view('shop.products')->with([
      'products' => $products,
      'categories' => $categories,
      'category' => $category ?? null
    ]);
  }

  public function createPhysicalProduct()
  {
    if (
      auth()->check()
      && auth()->user()->verified_id != 'yes'
      || !$this->settings->shop
      || !$this->settings->physical_products
    ) {
      abort(404);
    }

    return view('shop.add-physical-product');
  } // End method createPhysicalProduct

  public function storePhysicalProduct()
  {
    $categories = ShopCategories::count();
    $path = config('path.shop');

    // Currency Position
    if ($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      'description.required' => __('validation.required', ['attribute' => __('general.description')]),
      'tags.required' => __('validation.required', ['attribute' => __('general.tags')]),
      'description.min' => __('validation.min', ['attribute' => __('general.description')]),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'quantity.required' => __('validation.required', ['attribute' => __('general.quantity')]),
      'box_contents.required' => __('validation.required', ['attribute' => __('general.box_contents')]),
      'box_contents.max' => __('validation.max', ['attribute' => __('general.box_contents')]),
      'category.required' => __('validation.required', ['attribute' => __('general.category')]),
    ];

    // Media Files Preview
    $fileuploaderPreview = $this->request->input('fileuploader-list-preview');
    $fileuploaderPreview = json_decode($fileuploaderPreview, TRUE);

    if (!$fileuploaderPreview) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.image_preview_required')],
      ]);
    }

    $input = $this->request->all();

    $validator = Validator::make($input, [
      'name'     => 'required|min:5|max:100',
      'tags'     => 'required',
      'category' => Rule::requiredIf($categories),
      'description' => 'required|min:10',
      'price'       => 'required|numeric|min:' . $this->settings->min_price_product . '|max:' . $this->settings->max_price_product,
      'quantity' => 'required',
      'box_contents' => 'required|max:100',
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    } //<-- Validator

    // Validate length tags
    $tagsLength = explode(',', trim($this->request->tags, ','));

    foreach ($tagsLength as $tag) {
      if (strlen($tag) < 2) {
        return response()->json([
          'success' => false,
          'errors' => ['error' => __('general.error_length_tags')],
        ]);
      }
    }

    // Validate price and shipping fee
    if ($this->request->shipping_fee >= $this->request->price) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.error_price_shipping_fee')],
      ]);
    }

    $product              = new Products();
    $product->user_id     = auth()->id();
    $product->name        = $this->request->name;
    $product->type        = 'physical';
    $product->price       = $this->request->price;
    $product->shipping_fee = $this->request->shipping_fee;
    $product->country_free_shipping = $this->request->shipping_fee ? $this->request->country_free_shipping : false;
    $product->tags        = trim($this->request->tags, ',');
    $product->quantity     = $this->request->quantity;
    $product->box_contents     = $this->request->box_contents;
    $product->category     = $this->request->category;
    $product->description = trim(Helper::checkTextDb($this->request->description));
    $product->save();

    // Insert Images Preview
    if ($fileuploaderPreview) {
      foreach ($fileuploaderPreview as $key => $media) {
        MediaProducts::create([
          'products_id' => $product->id,
          'name' => $media['file'],
          'products_id' => $product->id
        ]);

        // Move file to Storage
        $this->moveFileStorage($media['file'], $path);
      }
    } // Insert Images Previews

    return response()->json([
      'success' => true,
      'url' => url('shop/product', $product->id)
    ]);
  } // End method storePhysicalProduct

  public function create()
  {
    if (
      auth()->check()
      && auth()->user()->verified_id != 'yes'
      || !$this->settings->shop
      || !$this->settings->digital_product_sale
    ) {
      abort(404);
    }

    return view('shop.add-product');
  } // End method create

  public function store()
  {
    $categories = ShopCategories::count();
    $path = config('path.shop');

    // Currency Position
    if ($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      'description.required' => __('validation.required', ['attribute' => __('general.description')]),
      'description.min' => __('validation.min', ['attribute' => __('general.description')]),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'category.required' => __('validation.required', ['attribute' => __('general.category')]),
    ];

    // Media Files Preview
    $fileuploaderPreview = $this->request->input('fileuploader-list-preview');
    $fileuploaderPreview = json_decode($fileuploaderPreview, TRUE);

    // Media File
    $fileuploaderFile = $this->request->input('fileuploader-list-file');
    $fileuploaderFile = json_decode($fileuploaderFile, TRUE);

    if (!$fileuploaderPreview) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.image_preview_required')],
      ]);
    }

    if (!$fileuploaderFile) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.file_required')],
      ]);
    }

    $input = $this->request->all();

    $validator = Validator::make($input, [
      'name'     => 'required|min:5|max:100',
      'tags'     => 'required',
      'category' => Rule::requiredIf($categories),
      'description' => 'required|min:10',
      'price'       => 'required|numeric|min:' . $this->settings->min_price_product . '|max:' . $this->settings->max_price_product,
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    } //<-- Validator

    // Validate length tags
    $tagsLength = explode(',', trim($this->request->tags, ','));

    foreach ($tagsLength as $tag) {
      if (strlen($tag) < 2) {
        return response()->json([
          'success' => false,
          'errors' => ['error' => __('general.error_length_tags')],
        ]);
      }
    }

    $product              = new Products();
    $product->user_id     = auth()->id();
    $product->name        = $this->request->name;
    $product->price       = $this->request->price;
    $product->tags        = trim($this->request->tags, ',');
    $product->category    = $this->request->category;
    $product->description = trim(Helper::checkTextDb($this->request->description));
    $product->save();

    // Insert Images Preview
    if ($fileuploaderPreview) {
      foreach ($fileuploaderPreview as $key => $media) {
        MediaProducts::create([
          'products_id' => $product->id,
          'name' => $media['file'],
          'products_id' => $product->id
        ]);

        // Move file to Storage
        $this->moveFileStorage($media['file'], $path);
      }
    } // Insert Images Previews

    // Update File
    if ($fileuploaderFile) {

      $local = 'temp/';

      foreach ($fileuploaderFile as $key => $media) {

        $uploaderfile = $media['file'];
        $img = public_path($local . $uploaderfile);
        $ext = explode('.', $uploaderfile);
        $mime = mime_content_type($img);

        Products::whereId($product->id)->update([
          'file' => $media['file'],
          'mime' => $mime,
          'extension' => $ext[1],
          'size' => Helper::formatBytes(filesize($img), 1)
        ]);

        // Move file to Storage
        $this->moveFileStorage($media['file'], $path);
      }
    } // Update File

    return response()->json([
      'success' => true,
      'url' => url('shop/product', $product->id)
    ]);
  } // End method store

  public function createCustomContent()
  {
    if (
      auth()->check()
      && auth()->user()->verified_id != 'yes'
      || !$this->settings->shop
      || !$this->settings->custom_content
    ) {
      abort(404);
    }

    return view('shop.add-custom-content');
  } // End method create

  public function storeCustomContent()
  {
    $categories = ShopCategories::count();
    $path = config('path.shop');

    // Currency Position
    if ($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      'description.required' => __('validation.required', ['attribute' => __('general.description')]),
      'tags.required' => __('validation.required', ['attribute' => __('general.tags')]),
      'description.min' => __('validation.min', ['attribute' => __('general.description')]),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'delivery_time.required' => __('validation.required', ['attribute' => __('general.delivery_time')]),
      'category.required' => __('validation.required', ['attribute' => __('general.category')]),
    ];

    // Media Files Preview
    $fileuploaderPreview = $this->request->input('fileuploader-list-preview');
    $fileuploaderPreview = json_decode($fileuploaderPreview, TRUE);

    if (!$fileuploaderPreview) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.image_preview_required')],
      ]);
    }

    $input = $this->request->all();

    $validator = Validator::make($input, [
      'name'     => 'required|min:5|max:100',
      'tags'     => 'required',
      'description' => 'required|min:10',
      'price'       => 'required|numeric|min:' . $this->settings->min_price_product . '|max:' . $this->settings->max_price_product,
      'delivery_time' => 'required',
      'category' => Rule::requiredIf($categories),
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    } //<-- Validator

    // Validate length tags
    $tagsLength = explode(',', trim($this->request->tags, ','));

    foreach ($tagsLength as $tag) {
      if (strlen($tag) < 2) {
        return response()->json([
          'success' => false,
          'errors' => ['error' => __('general.error_length_tags')],
        ]);
      }
    }

    $product              = new Products();
    $product->user_id     = auth()->id();
    $product->name        = $this->request->name;
    $product->type        = 'custom';
    $product->price       = $this->request->price;
    $product->delivery_time = $this->request->delivery_time;
    $product->tags        = trim($this->request->tags, ',');
    $product->category    = $this->request->category;
    $product->description = trim(Helper::checkTextDb($this->request->description));
    $product->save();

    // Insert Images Preview
    if ($fileuploaderPreview) {
      foreach ($fileuploaderPreview as $key => $media) {
        MediaProducts::create([
          'products_id' => $product->id,
          'name' => $media['file'],
          'products_id' => $product->id
        ]);

        // Move file to Storage
        $this->moveFileStorage($media['file'], $path);
      }
    } // Insert Images Previews

    return response()->json([
      'success' => true,
      'url' => url('shop/product', $product->id)
    ]);
  } // End method storeCustomContent

  /**
   * Move file to Storage
   */
  protected function moveFileStorage($file, $path)
  {
    $localFile = public_path('temp/' . $file);

    // Move the file...
    Storage::putFileAs($path, new File($localFile), $file);

    // Delete temp file
    unlink($localFile);
  } // end method moveFileStorage

  public function update()
  {
    $product = Products::whereId($this->request->id)->whereUserId(auth()->id())->firstOrFail();

    // Currency Position
    if ($this->settings->currency_position == 'right') {
      $currencyPosition =  2;
    } else {
      $currencyPosition =  null;
    }

    $messages = [
      'description.required' => __('validation.required', ['attribute' => __('general.description')]),
      'tags.required' => __('validation.required', ['attribute' => __('general.tags')]),
      'description.min' => __('validation.min', ['attribute' => __('general.description')]),
      'price.min' => __('general.amount_minimum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'price.max' => __('general.amount_maximum' . $currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
      'delivery_time.required' => __('validation.required', ['attribute' => __('general.delivery_time')]),
      'quantity.required' => __('validation.required', ['attribute' => __('general.quantity')]),
      'box_contents.required' => __('validation.required', ['attribute' => __('general.box_contents')]),
      'box_contents.max' => __('validation.max', ['attribute' => __('general.box_contents')]),
      'category.required' => __('validation.required', ['attribute' => __('general.category')]),
    ];

    $input = $this->request->all();

    $validator = Validator::make($input, [
      'name'     => 'required|min:5|max:100',
      'tags'     => 'required',
      'description' => 'required|min:10',
      'price'       => 'required|numeric|min:' . $this->settings->min_price_product . '|max:' . $this->settings->max_price_product,
      'category'     => 'required',
      'delivery_time' => Rule::requiredIf($product->type == 'custom'),
      'quantity' => Rule::requiredIf($product->type == 'physical'),
      'box_contents' => [
        'max:100',
        Rule::requiredIf($product->type == 'physical')
      ],
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    } //<-- Validator

    // Validate length tags
    $tagsLength = explode(',', trim($this->request->tags, ','));

    foreach ($tagsLength as $tag) {
      if (strlen($tag) < 2) {
        return response()->json([
          'success' => false,
          'errors' => ['error' => __('general.error_length_tags')],
        ]);
      }
    }

    $product->name        = $this->request->name;
    $product->price       = $this->request->price;
    $product->shipping_fee = $this->request->shipping_fee ?? false;
    $product->country_free_shipping = $this->request->country_free_shipping ?? false;
    $product->tags        = trim($this->request->tags, ',');
    $product->description = trim(Helper::checkTextDb($this->request->description));
    $product->delivery_time = $this->request->delivery_time ?? false;
    $product->quantity     = $this->request->quantity ?? false;
    $product->box_contents = $this->request->box_contents ?? false;
    $product->category    = $this->request->category;
    $product->status      = $this->request->status ?? '0';
    $product->save();

    return response()->json([
      'success' => true,
      'url' => url('shop/product', $product->id)
    ]);
  } // End method store

  public function show($id)
  {
    if (!$this->settings->shop) {
      abort(404);
    }

    $product = Products::findOrFail($id);

    if (
      !$product->status
      && auth()->id()
      != $product->user()->id
      || !$product->status
      && auth()->check()
      && auth()->user()->role == 'normal'
    ) {
      abort(404);
    }

    $uri = $this->request->path();

    if (str_slug($product->name) == '') {
      $slugUrl  = '';
    } else {
      $slugUrl  = '/' . str_slug($product->name);
    }

    $urlImage = 'shop/product/' . $product->id . $slugUrl;

    //<<<-- * Redirect the user real page * -->>>
    $uriImage     =  $this->request->path();
    $uriCanonical = $urlImage;

    if ($uriImage != $uriCanonical) {
      return redirect($uriCanonical);
    }

    // Tags
    $tags = explode(',', $product->tags);

    // Previews
    $previews = count($product->previews);

    if (auth()->check()) {
      $verifyPurchaseUser = $product->purchases()
        ->whereUserId(auth()->id())
        ->first();
    }

    // Total Items of User
    $userProducts = $product->user()->products()->whereStatus('1');

    return view('shop.show')->with([
      'product' => $product,
      'userProducts' => $userProducts,
      'tags' => $tags,
      'previews' => $previews,
      'verifyPurchaseUser' => $verifyPurchaseUser ?? null,
      'totalProducts' => $userProducts->count()
    ]);
  } // End method show

  public function buy()
  {
    // Find item exists
    $item = Products::findOrFail($this->request->id);

    // Shipping fee
    $shippingFee = $item->country_free_shipping <> auth()->user()->countries_id ? $item->shipping_fee : 0.00;
    $finalPriceItem = Helper::amountGrossProductShop($item->price, $shippingFee);
    $priceFixed = ($item->price + $shippingFee);

    // Verify that the user has not buy
    if (
      Purchases::whereUserId(auth()->id())
      ->whereProductsId($this->request->id)
      ->first()
      && $item->type == 'digital'
    ) {
      return response()->json([
        "success" => true,
        'url' => url('product/download', $item->id)
      ]);
    }

    // Check that the user has sufficient balance
    if (auth()->user()->wallet < $finalPriceItem) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.not_enough_funds')]
      ]);
    }

    // Check availability (stock)
    if ($item->type == 'physical' && $item->quantity == 0) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.out_stock')]
      ]);
    }

    $messages = [
      'description_custom_content.required' => __('validation.required', ['attribute' => __('general.details_custom_content')]),
      'address.required' => __('validation.required', ['attribute' => __('general.address')]),
      'city.required' => __('validation.required', ['attribute' => __('general.city')]),
      'zip.required' => __('validation.required', ['attribute' => __('general.zip')]),
      'phone.required' => __('validation.required', ['attribute' => __('general.phone')]),
      'phone.regex' => __('validation.regex', ['attribute' => __('general.phone')]),
      'phone.min' => __('validation.min', ['attribute' => __('general.phone')]),
    ];

    $validator = Validator::make($this->request->all(), [
      'description_custom_content' => Rule::requiredIf($item->type == 'custom'),
      'address' => Rule::requiredIf($item->type == 'physical'),
      'city' => Rule::requiredIf($item->type == 'physical'),
      'zip' => Rule::requiredIf($item->type == 'physical'),
      'phone' => [
        'regex:/^([0-9\s\-\+\(\)]*)$/',
        'min:10',
        Rule::requiredIf($item->type == 'physical')
      ],
    ], $messages);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    } //<-- Validator

    // Admin and user earnings calculation
    $earnings = $this->earningsAdminUser($item->user()->custom_fee, $priceFixed, null, null);

    //== Insert Transaction
    $txn = $this->transaction(
      'purchase_' . str_random(25),
      auth()->id(),
      false,
      $item->user()->id,
      $priceFixed,
      $earnings['user'],
      $earnings['admin'],
      'Wallet',
      'purchase',
      $earnings['percentageApplied'],
      auth()->user()->taxesPayable()
    );

    // Subtract user funds
    auth()->user()->decrement('wallet', $finalPriceItem);

    // Add Earnings to User
    $item->user()->increment('balance', $earnings['user']);
!
    // Insert Purchase
    $purchase = new Purchases();
    $purchase->transactions_id = $txn->id;
    $purchase->user_id = auth()->id();
    $purchase->products_id = $item->id;
    $purchase->delivery_status = $item->type == 'digital' ? 'delivered' : 'pending';
    $purchase->description_custom_content = $this->request->description_custom_content;
    $purchase->address = $this->request->address;
    $purchase->city = $this->request->city;
    $purchase->zip = $this->request->zip;
    $purchase->phone = $this->request->phone;
    $purchase->expired_at = $item->type == 'custom' ? now()->add($item->delivery_time, 'days') : null;
    $purchase->save();

    if ($item->type == 'physical' && $item->quantity != 0) {
      // Subtract an item from stock
      $item->decrement('quantity', 1);
    }

    // Send Notification to Creator
    Notifications::send($item->user()->id, auth()->id(), 15, $item->id);

    // Send Email to Creator
    try {
      $item->user()->notify(new NewSale($purchase));
    } catch (\Exception $e) {
      \Log::info('Error send email to creator on sale - ' . $e->getMessage());
    }

    if ($item->type == 'digital') {
      return response()->json([
        'success' => true,
        'url' => url('shop/product', $item->id)
      ]);
    } else {
      return response()->json([
        'success' => true,
        'buyCustomContent' => true,
        'wallet' => Helper::userWallet()
      ]);
    }
  } // End method buy

  public function download($id)
  {
    $item = Products::whereId($id)
      ->whereType('digital')
      ->firstOrFail();

    $file = $item->purchases()
      ->where('user_id', auth()->id())
      ->first();

    if (!$file && auth()->user()->role != 'admin') {
      abort(404);
    }

    $pathFile = config('path.shop') . $item->file;

    $headers = [
      'Content-Type:' => $item->mime,
      'Cache-Control' => 'no-cache, no-store, must-revalidate',
      'Pragma' => 'no-cache',
      'Expires' => '0'
    ];

    return Storage::download($pathFile, $item->name . '.' . $item->extension, $headers);
  } // End method download

  public function destroy($id)
  {
    $item = Products::whereId($id)
      ->where('user_id', auth()->id())
      ->firstOrFail();

    $path = config('path.shop');

    // Delete Notifications
    Notifications::whereType(15)->whereTarget($item->id)->delete();

    // Delete Reports
    $reports = Reports::whereReportId($id)->whereType('item')->get();

    if (isset($reports)) {
      foreach ($reports as $report) {
        $report->delete();
      }
    }

    // Delete Preview
    foreach ($item->previews as $previews) {
      Storage::delete($path . $previews->name);
    }

    // Delete file
    Storage::delete($path . $item->file);

    // Delete purchases
    $item->purchases()->delete();

    // Delete item
    $item->delete();

    return response()->json([
      'success' => true,
      'url' => url(auth()->user()->username)
    ]);
  } // End method download

  public function deliveredProduct($id)
  {
    $purchase = auth()->user()->sales()
      ->whereDeliveryStatus('pending')
      ->where('purchases.id', $id)
      ->firstOrFail();

    $purchase->delivery_status = 'delivered';
    $purchase->save();

    return response()->json([
      'success' => true
    ]);
  } // end deliveredProduct

  public function rejectOrder($id)
  {
    $purchase = auth()->user()->sales()
      ->whereDeliveryStatus('pending')
      ->where('purchases.id', $id)
      ->firstOrFail();

    $amount = $purchase->transactions()->amount;

    $taxes = TaxRates::whereIn('id', collect(explode('_', $purchase->transactions()->taxes)))->get();
    $totalTaxes = ($amount * $taxes->sum('percentage') / 100);

    // Total paid by buyer
    $amountRefund = number_format($amount + $purchase->transactions()->transaction_fee + $totalTaxes, 2, '.', '');

    // Get amount referral (if exist)
    $this->deductReferredBalanceByRefund($purchase->transactions());

    // Add funds to wallet buyer
    $purchase->user()->increment('wallet', $amountRefund);

    // Remove creator funds
    if (auth()->user()->balance <> 0.00) {
      auth()->user()->decrement('balance', $purchase->transactions()->earning_net_user);
    } else {
      // If the creator has withdrawn their entire balance remove from withdrawal
      $withdrawalPending = Withdrawals::whereUserId(auth()->id())->whereStatus('pending')->first();

      if ($withdrawalPending) {
        $withdrawalPending->decrement('amount', $amountRefund);
      }
    }

    // Delete transaction
    $purchase->transactions()->delete();

    // Delete purchase
    $purchase->delete();

    return response()->json([
      'success' => true
    ]);
  } // end rejectOrder

  public function report()
  {
    $data = Reports::firstOrNew([
      'user_id' => auth()->id(),
      'report_id' => $this->request->id,
      'type' => 'item'
    ]);

    $validator = Validator::make($this->request->all(), [
      'reason' => 'required|in:item_not_received,spoofing,copyright,privacy_issue,violent_sexual,fraud',
      'message' => 'max:200',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    if ($data->exists) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.already_sent_report')],
      ]);
    } else {
      $data->reason = $this->request->reason;
      $data->message = $this->request->message ?: null;
      $data->save();

      return response()->json([
        'success' => true,
        'text' => __('general.reported_success'),
      ]);
    }
  } // end report

}
