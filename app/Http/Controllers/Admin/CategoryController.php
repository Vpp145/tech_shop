<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\AdminsRole;
use Validator;
use Session;
use Image;
use Auth;

class CategoryController extends Controller
{
    public function categories() {
        Session::put('page', 'categories');
        $categories = Category::with('parentCategory')->get()->toArray();
        $categories = json_decode(json_encode($categories), true);

        $admin_id = Auth::guard('admin')->user()->id;
        $module_count = AdminsRole::where(['subadmin_id' => $admin_id, 'module' => 'categories'])->count();

        if (Auth::guard('admin')->user()->type == 'admin') {
            $categories_module['view_access'] = 1;
            $categories_module['edit_access'] = 1;
            $categories_module['full_access'] = 1;
        } else {
            if ($module_count == 0) {
                $message = 'This feature is restricted to this Sub Admins';
                return redirect('admin/dashboard')->with('error_message', $message);
            }

            $categories_module = AdminsRole::where(['subadmin_id' => $admin_id, 'module' => 'categories'])->first();

            if ($categories_module->view_access == 0 && $categories_module->edit_access == 0 && $categories_module->full_access == 0) {
                $message = 'This feature is restricted to this Sub Admins';
                return redirect('admin/dashboard')->with('error_message', $message);
            }

            $categories_module = $categories_module->toArray();
        }

        return view('admin.categories.categories')->with(compact('categories', 'categories_module'));
    }

    public function updateCategoryStatus(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }

            Category::where('id', $data['category_id'])->update(['status' => $status]);
            return response()->json(['status'=>$status, 'category_id'=>$data['category_id']]);
        }
    }

    public function deleteCategory($id) {
            Category::where(['id' => $id])->delete();
            return response()->back()->with('success_message', 'category deleted successfully!!');
    }

    public function addEditCategory(Request $request, $id = null) {
        if ($id == "") {
            $title = "Add Category";
            $category = new Category;
            $message = 'Category Added successfully';
        } else {
            $title = "Edit Category";
            $category = Category::find($id);
            // $message = 'Category updated successfully';
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['category_discount'] == '') {
                $data['category_discount'] = 0;
            }

            if($request->hasFile('category_image')) {
                $image_tmp = $request->file('category_image');
                if($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $image_name = rand(111, 99999).'.'.$extension;
                    $image_path = 'front/images/category_images/'.$image_name;
                    Image::make($image_tmp)->save($image_path);

                    $category->category_image = $image_name;
                }
            } else {
                $category->category_image = '';
            }

            // $category->parent_id = $data['parent_id'];
            $category->category_name = $data['category_name'];
            $category->category_discount = $data['category_discount'];
            $category->description = $data['description'] ?? '';
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'] ?? '';
            $category->meta_description = $data['meta_description'] ?? '';
            $category->meta_keywords = $data['meta_keywords'] ?? '';
            $category->status = 1;
            $category->save();
            return redirect('admin/categories')->with('success_message', $message);
        }

        return view('admin.categories.add_edit_category', compact('title', 'category'));
    }
}