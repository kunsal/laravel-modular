<?php
/*
 * Helper method for loading default theme assets
 */
if(!function_exists('theme')){
    function theme($path = '')
    {
        $config = app('config')->get('cms.theme');
        if($path == ''){

            return asset($config['folder'].'/'.$config['active'].'/assets/');
        }
        return asset($config['folder'].'/'.$config['active'].'/assets/'.$path);
    }
}

if(!function_exists('theme_uploads')){
    function theme_uploads($path)
    {
        $config = app('config')->get('cms.theme');
        return url($config['folder'].'/'.$config['active'].'/uploads/'.$path);
    }
}

if(!function_exists('frontend')){
    function frontend($path = '')
    {
        if(!empty($path)){
            return url('front-assets/'.$path);
        }
        return url('front-assets/');
    }
}

if(!function_exists('theme2')){
    function theme2($path = '')
    {
        if(!empty($path)){
            return url('front-assets/theme2/'.$path);
        }
        return url('front-assets/theme2/');
    }
}

if(!function_exists('uploads_url')){
    function uploads_url($path)
    {
        return url('uploads/'.$path);
    }
}

if (!function_exists('make_slug'))
{
    function make_slug($str)
    {
        $str = strtolower($str);
        $str = preg_replace('/[^0-9a-z-]+/', '-', $str);
        return trim($str,'-');
    }
}

if (!function_exists('upload_image'))
{
    function upload_image($image, $folder, $resize_width='', $resize_height='', $thumb_folder='', $quality = 60)
    {
        //$image = \App\Libraries\Image::class;
        $array = upload($image, $folder,'');
        if(!empty($resize_width) || !empty($resize_height)){
            $resize_folder = public_path().'/uploads/'.$folder.'/'.$thumb_folder.'/'.$array['filename'];
            resize($array['path'], $resize_width, $resize_height, $resize_folder, $quality);
        }
        return $array;
    }

}

if (!function_exists('upload_image_and_resize'))
{
    function upload_image_and_resize($image, $folder, $sizes=array(), $quality = 60)
    {
        //$image = \App\Libraries\Image::class;
        $array = upload($image, $folder,'');
        if(!empty($sizes)){
            foreach($sizes as $size){
                $resize_folder = public_path().'/uploads/'.$folder.'/'.$size.'/'.$array['filename'];
                $width_height = explode('x', $size);
                $resize_width = $width_height[0];
                $resize_height = $width_height[1];
                resize($array['path'], $resize_width, $resize_height, $resize_folder, $quality, true);
            }

        }
        return $array;
    }

}

if(!function_exists('add_watermark')){
    function add_watermark($path){
        // create new Intervention Image
        $img = \Image::make(public_path().$path);

// paste another image
        $img->insert(public_path('/uploads/cmw.png'), 'bottom-left', 10, 10);
        $img->insert(public_path('/uploads/cmw.png'), 'top-right', 10, 10);
        $img->save(public_path().$path);

// create a new Image instance for inserting
        /*$watermark = \Image::make(public_path('/uploads/cmw.png'));
        $img->insert($watermark, 'center');*/

// insert watermark at bottom-right corner with 10px offset
        //  $img->insert(public_path('/uploads/cmw.png'), 'bottom-right', 10, 10);
    }
}

function upload($file, $dir = null, $name="Photo")
{
    if ($file)
    {
        // Generate random dir
        // if ( ! $dir) $dir = str_random(8);
        // Create directory if not exists
        //if (!is_dir($dir)) mkdir($dir);
        // Get file info and try to move
        $destination = public_path() .  '/uploads/' . $dir;
        $original_name = $file->getClientOriginalName();
        $filename    = $name.'-'.str_random(10).'.'.$file->getClientOriginalExtension();
        $path        = '/uploads/' . $dir . '/' . $filename;
        $uploaded    = $file->move($destination, $filename);

        if ($uploaded) return array('path'=>$path,'filename'=>$filename, 'original_name' => $original_name, 'size' => $file->getClientSize());
    }
}

function upload_without_rename($file, $dir = null)
{
    if ($file)
    {
        // Generate random dir
        // if ( ! $dir) $dir = str_random(8);
        // Create directory if not exists
        //if (!is_dir($dir)) mkdir($dir);
        // Get file info and try to move
        $destination = public_path() .  '/uploads/' . $dir;
        $filename = $file->getClientOriginalName();
        $path        = '/uploads/' . $dir . '/' . $filename;
        $uploaded    = $file->move($destination, $filename);

        if ($uploaded) return array('path'=>$path,'filename'=>$filename, 'size' => $file->getClientSize());
    }
}

function file_upload($file, $dir = null,$name)
{
    if ($file)
    {
        // Generate random dir
        if ( ! $dir) $dir = str_random(8);

        // Get file info and try to move
        $destination = public_path() .  '/uploads/' . $dir;
        $filename    = $name.'-'.str_random(10).'.'.$file->getClientOriginalExtension();
        $path        = '/uploads/' . $dir . '/' . $filename;
        $uploaded    = $file->move($destination, $filename);

        if ($uploaded) return array('path'=>$path,'filename'=>$filename);
    }
}

function resize($path, $width, $height, $folder, $quality, $force_resize = false )
{
    $image = \Image::make(public_path().$path);
    $folder = explode('/', $folder);
    $file_name = array_pop($folder);
    $folder = implode('/', $folder);
    // Create dir if missing
    if ( ! File::isDirectory($folder)) @File::makeDirectory($folder);

    // If height is null
    if($width != '' && $height == ''){
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    // If width is null
    if($width == '' && $height !== ''){
        $image->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    // If height and width value are given
    if($width != '' && $height !== ''){
        if($force_resize == true){
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }else{
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

    }

    $image->save($folder.'/'.$file_name, $quality);
}

function decode_size($number){
    $number = (int) $number;
    $kb = $number/1000;
    if($kb > 1000){
        return $kb/1000 .'MB';
    }
    return $kb . 'KB';
}


function open_nav(array $array)
{
    if(isset($array['children']) && !empty($array['children'])){
        foreach($array['children'] as $child){
            if(/*Request::is($child['active']) || */in_array(Request::path(), $child['active'])){
                return true;
            }
        }
    }
    return false;
}

function is_child(array $array)
{
    if(isset($array['children']) && !empty($array['children'])){
        foreach($array['children'] as $child){
            if(in_array(Request::path(), $child['active'])){
                return true;
            }
        }
    }
    return false;
}

function is_parent($link){
    return (isset($link['children']) && count($link['children'])) ? true : false;
}

function match_asteriks($links){
    //$links = ['users', 'user*', 'create/user'];
    foreach ($links as $link){
        if(preg_match('/(\w)*\*/', $link)){
            // Get the request path
            $path = explode('/',request()->path());
            if(count($path)>1){
                if(preg_match('/'.$path[0].'\/'.$path[1].'\*/', $link)){
                    return true;
                }
            }
        }
    }
    return false;
}

function is_active($links){
    if(in_array(request()->path(), $links)){
        return true;
    }elseif(match_asteriks($links)){
        return true;
    }else{
        return false;
    }
}

function strip_role($uri){
    $uri = explode('/', $uri);
    unset($uri[0]);
    return implode('/', $uri);
}

function mysql_date($date)
{
    return str_replace('/', '-', $date);
}


function route_back($route, $param=null)
{
    return route($route,$param).'?r=1';
}

function download($filename, $path=''){
    if(!empty($filename)){
        // Specify file path.
        // $path = '';
        $download_file =  $path.$filename;
        // Check file is exists on given path.
        if(file_exists($download_file))
        {
            // Getting file extension.
            $extension = explode('.',$filename);
            $extension = $extension[count($extension)-1];
            // For Gecko browsers
            header('Content-Transfer-Encoding: binary');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            // Supports for download resume
            header('Accept-Ranges: bytes');
            // Calculate File size
            header('Content-Length: ' . filesize($download_file));
            header('Content-Encoding: none');
            // Change the mime type if the file is not PDF
            header('Content-Type: application/'.$extension);
            // Make the browser display the Save As dialog
            header('Content-Disposition: attachment; filename=' . $filename);
            readfile($download_file);
            exit;
        }
        else
        {
            echo 'File does not exists on given path';
        }

    }
}

// Event view for auditing

function event_view($model, $event){
    $name = strtolower((new \ReflectionClass($model))->getShortName());
    return $event.'_'.$name;
}

function prepend_zeros($num, $digits=8){

    // Is number less than number of digits?
    if(strlen($num) < $digits){
        // Get the remaining digits to make number of digits
        $rem = $digits - strlen($num);
        $zeros = '';
        // Loop and add zeros to $zeros
        for($i = 0; $i < $rem; $i++){
            $zeros .= '0';
        }
        // Concat $zeros and number
        return $zeros.$num;
    }
    return $num;
}

function preg_array_key_exists($pattern, $array) {
    $keys = array_keys($array);
    return (int) preg_grep($pattern,$keys);
}

function file_type($filename){
    $type = explode('.',$filename);
    return strtolower($type[count($type)-1]);
}

function is_image($file)
{
    if(in_array(file_type($file), ['png','jpg','gif','jpeg'])){
        return true;
    }
    return false;
}

function all_permissions(){
    // Retrieve all routes of the application
    $routes = \Illuminate\Support\Facades\Route::getRoutes()->getRoutes();
    // Initialize permissions array
    $permissions = [];
    // Loop through all routes
    foreach($routes as $route){
        if($route->getName() == ''){
            continue;
        }
        // Fetch and store only get method routes
        if(in_array('get', array_map('strtolower', $route->methods()))){
            $permissions[$route->getName()] = str_replace('.', ' ', $route->getName());
        }

    }
    return $permissions;
}

function selected_permissions($perms){
    return json_decode($perms, true);
}

function get_ol($array, $child=false){
    $str = '';

    if(count($array)){
        $str = $child == false ? '<ol class="sortable">' : '<ol>';
        foreach($array as $item){
            $str .= '<li id="list_'.$item['page_id'].'" class="menu-item">';
            $str .= '<a>'.page_title($item['page_id']).
                '<span class="pull-right remove-from-menu" data-page-id="'.$item['page_id'].'">
                            <i class="fa fa-trash text-danger" style="cursor:pointer" title="Remove from Menu"></i>
                        </span>
                    </a>';
            // Do we have any children?
            if(isset($item['children']) && count($item['children'])){
                $str .= get_ol($item['children'], true);
            }
            $str .= '</li>' . PHP_EOL;
        }
        $str .= '</ol>' . PHP_EOL;
    }

    return $str;
}

function page_title($id)
{
    return \App\Modules\Pages\Models\Page::select(['title'])->where('id', $id)->first()['title'];
}

function active_uri($uri)
{
    return \Illuminate\Support\Facades\Request::path() == $uri ? true : false;
}

function naira()
{
    return '₦';
}

function naira_format($number){
    return naira().number_format($number, 2);
}

function dollar_format($number){
    return '$'.number_format($number, 2);
}

function setting()
{
    return \App\Modules\Settings\Models\Setting::find(1);
}

// Buttons

function action_btns($route_name, $entity, $param='id',$param_key='id',$show=false, $show_param='', $delete=true)
{
    $edit = $confirm = $show_btn = '';

    if(\Illuminate\Support\Facades\Gate::allows($route_name.'.edit')){
        $edit = '<a href="'.route($route_name.'.edit', $entity->$param).'" class="btn btn-info btn-xs edit" title="Edit"><i class="fa fa-edit"></i></a>';
    }
    if($delete == true){
        if(\Illuminate\Support\Facades\Gate::allows($route_name.'.destroy')){
            $confirm = '<a href="'.url($route_name.'/delete/'.$entity->$param.'/'.$param_key).'" title="Delete" class="btn btn-danger btn-xs delete" onclick="if(confirm(\'Are you sure you want to delete this item?\')){return true}return false">
            <i class="fa fa-trash-o"></i>
            </a>';
        }
    }

    if($show == true) {
        $show_btn = '<a title="View" href="'.route($route_name.'.show', [$entity->$param]).'" class="btn btn-warning btn-xs edit"><i class="fa fa-search"></i></a>';
    }
    return $edit.' '.$show_btn.' '.$confirm;
}

if(!function_exists('save_btn')){
    function save_btn($save_value, $back_route, $show_save_exit = true)
    {
        $save = '<input type="submit" class="btn btn-primary" value="'.$save_value.'">';
        $save_exit = $show_save_exit == true ? '<input type="submit" class="btn btn-success" value="Save & Exit" name="save_exit">':'';
        $cancel_btn = '<a href="#" class="btn btn-default" onclick="window.history.back()">Cancel</a>';
        return '<div class="btn-group">'.$save.'&nbsp;'.$save_exit.'&nbsp;'.$cancel_btn.'</div>';
    }
}

if(!function_exists('delete_link')){
    function delete_link($route){
        return '<form method="post" action="'.$route.'">'
            .csrf_field().
            '<input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-xs btn-danger delete-me">Delete</button>
               </form>';
    }
}

function add_new_btn($route, $btn_txt)
{
    return '<div class="row">
                <div class="col-lg-12">
                    <a href="'.route($route) .'" class="btn btn-primary pull-right">'.$btn_txt.'</a>
                </div>
            </div>
            <br>';
}

function back_btn($route, $btn_txt)
{
    return '<a href="'.route($route).'" class="btn btn-warning">'.$btn_txt.'</a>';
}

