<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ClassTableController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Students\AttendanceController;
use App\Http\Controllers\Students\FeesInvoicesController;
use App\Http\Controllers\Students\ReceiptStudentsController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\TermSubjectController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::get('/', 'HomeController@index')->name('selection');

Route::group(['namespace' => 'Auth'], function () {

    Route::get('/login/{type}', 'LoginController@loginForm')->middleware('guest')->name('login.show');
    Route::post('/login', 'LoginController@login')->name('login');
    Route::get('/logout/{type}', 'LoginController@logout')->name('logout');
});
//==============================Translate all pages============================
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth']
    ],
    function () {

        //==============================dashboard============================
        Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

        //==============================generel============================
        Route::get('/generel', [GeneralController::class, 'academicYears'])->name('generel');
        Route::get('/show_grades/{academicYear}', [GeneralController::class, 'show_grades'])->name('show_grades');
        Route::get('/show_classrooms/{grade}', [GeneralController::class, 'show_classrooms'])->name('show_classrooms');

        Route::group([
            'middleware' => ['CheckAcademicYeay']
        ], function () {
            Route::get('/show_terms/{classroom}', [GeneralController::class, 'show_terms'])->name('show_terms');
            Route::get('/show_sections/{term}', [GeneralController::class, 'show_sections'])->name('show_sections');
            Route::get('/show_sections_settings/{section}', [GeneralController::class, 'show_sections_settings'])->name('show_sections_settings');

            //==============================section attendance============================
            Route::get('/show_section_subjects_day', [AttendanceController::class, 'show_section_subjects_day'])->name('show_section_subjects_day');
            Route::get('create_attendance/{subject}', [AttendanceController::class, 'create_attendance'])->name('create_attendance');
            Route::get('createAttendenceExpect/{date?}/{subjectId?}', [AttendanceController::class, 'createAttendenceExpect'])->name('createAttendenceExpect');
            Route::post('attendenceDelete', [AttendanceController::class, 'attendenceDelete'])->name('attendenceDelete');
            Route::post('store_attendance', [AttendanceController::class, 'store_attendance'])->name('store_attendance');
            Route::post('storeAttendenceExpect', [AttendanceController::class, 'storeAttendenceExpect'])->name('storeAttendenceExpect');

            //==============================class tabel============================
            Route::get('/show_class_tabel', [ClassTableController::class, 'show_class_tabel'])->name('show_class_tabel');
            Route::post('/save_class_tabel', [ClassTableController::class, 'save_class_tabel'])->name('save_class_tabel');

            //==============================section marks============================
            Route::get('/show_section_subjects', [GeneralController::class, 'show_section_subjects'])->name('show_section_subjects');
            Route::get('/get_sections_marks/{subjectId}', [GeneralController::class, 'get_sections_marks'])->name('get_sections_marks');
            Route::post('/store_sections_marks', [GeneralController::class, 'store_sections_marks'])->name('store_sections_marks');

            //==============================section exams============================
            Route::get('/get_sections_exams', [GeneralController::class, 'get_sections_exams'])->name('get_sections_exams');
            Route::post('/store_sections_exams', [GeneralController::class, 'store_sections_exams'])->name('store_sections_exams');
            Route::post('/update_sections_exams', [GeneralController::class, 'update_sections_exams'])->name('update_sections_exams');
            Route::post('/delete_sections_exams', [GeneralController::class, 'delete_sections_exams'])->name('delete_sections_exams');

            //==============================section teachers============================
            Route::get('/show_sections_teachers', [GeneralController::class, 'show_sections_teachers'])->name('show_sections_teachers');
            Route::get('related_with_subjects/{teacher}', [GeneralController::class, 'related_with_subjects'])->name('related_with_subjects');
            Route::post('save_related_with_subjects', [GeneralController::class, 'save_related_with_subjects'])->name('save_related_with_subjects');

            //==============================section students============================
            Route::get('/show_sections_students', [GeneralController::class, 'show_sections_students'])->name('show_sections_students');
            Route::get('/get_sections_students', [GeneralController::class, 'get_sections_students'])->name('get_sections_students');
            Route::post('/releatoin_section_students', [GeneralController::class, 'releatoin_section_students'])->name('releatoin_section_students');
            Route::post('/unreleatoin_section_students', [GeneralController::class, 'unreleatoin_section_students'])->name('unreleatoin_section_students');

            //==============================terms subjects============================
            Route::get('/show_term_subjects', [TermSubjectController::class, 'show_term_subjects'])->name('show_term_subjects');
            Route::get('/get_term_subjects', [TermSubjectController::class, 'get_term_subjects'])->name('get_term_subjects');
            Route::post('/releatoin_term_subjects', [TermSubjectController::class, 'releatoin_term_subjects'])->name('releatoin_term_subjects');
            Route::post('/unreleatoin_term_subjects', [TermSubjectController::class, 'unreleatoin_term_subjects'])->name('unreleatoin_term_subjects');
            //==============================terms============================
            Route::group(['namespace' => 'Terms'], function () {
                Route::post('terms_store', [TermController::class, 'store'])->name('terms_store');
                Route::patch('terms_update', [TermController::class, 'update'])->name('terms_update');
                Route::delete('terms_delete', [TermController::class, 'destroy'])->name('terms_delete');
            });
        });

        //==============================academic year============================
        Route::group(['namespace' => 'AcademicYear'], function () {
            Route::get('academic_years', [AcademicYearController::class, 'index'])->name('academic_years');
            Route::post('academic_years_store', [AcademicYearController::class, 'store'])->name('academic_years_store');
            Route::patch('academic_years_update', [AcademicYearController::class, 'update'])->name('academic_years_update');
            Route::delete('academic_years_delete', [AcademicYearController::class, 'destroy'])->name('academic_years_delete');
        });

        //==============================dashboard============================
        Route::group(['namespace' => 'Grades'], function () {
            Route::resource('Grades', 'GradeController');
        });

        //==============================Classrooms============================
        Route::group(['namespace' => 'Classrooms'], function () {
            Route::resource('Classrooms', 'ClassroomController');
            Route::post('delete_all', 'ClassroomController@delete_all')->name('delete_all');

            Route::post('Filter_Classes', 'ClassroomController@Filter_Classes')->name('Filter_Classes');
        });


        //==============================Sections============================

        Route::group(['namespace' => 'Sections'], function () {

            Route::resource('Sections', 'SectionController');

            Route::get('/classes/{id}', 'SectionController@getclasses');
        });

        //==============================parents============================

        Route::view('add_parent', 'livewire.show_Form')->name('add_parent');

        //==============================Teachers============================
        Route::group(['namespace' => 'Teachers'], function () {
            Route::resource('Teachers', 'TeacherController');
        });


        //==============================Students============================
        Route::group(['namespace' => 'Students'], function () {
            Route::resource('Students', 'StudentController');
            Route::resource('online_classes', 'OnlineClasseController');
            Route::get('indirect_admin', 'OnlineClasseController@indirectCreate')->name('indirect.create.admin');
            Route::post('indirect_admin', 'OnlineClasseController@storeIndirect')->name('indirect.store.admin');
            Route::resource('Graduated', 'GraduatedController');
            Route::resource('Promotion', 'PromotionController');
            Route::resource('Fees_Invoices', 'FeesInvoicesController');
            Route::resource('Fees', 'FeesController');
            Route::resource('receipt_students', 'ReceiptStudentsController');
            Route::get('print_invoice/{id}', [ReceiptStudentsController::class, 'print_invoice'])->name('print_invoice');
            Route::resource('ProcessingFee', 'ProcessingFeeController');
            Route::resource('Payment_students', 'PaymentController');
            Route::resource('Attendance', 'AttendanceController');
            Route::get('download_file/{filename}', 'LibraryController@downloadAttachment')->name('downloadAttachment');
            Route::resource('library', 'LibraryController');
            Route::post('Upload_attachment', 'StudentController@Upload_attachment')->name('Upload_attachment');
            Route::get('Download_attachment/{studentsname}/{filename}', 'StudentController@Download_attachment')->name('Download_attachment');
            Route::post('Delete_attachment', 'StudentController@Delete_attachment')->name('Delete_attachment');
        });

        //==============================subjects============================
        Route::group(['namespace' => 'Subjects'], function () {
            Route::resource('subjects', 'SubjectController');
        });

        //==============================Quizzes============================
        // Route::group(['namespace' => 'Quizzes'], function () {
        //     Route::resource('Quizzes', 'QuizzController');
        // });

        //==============================questions============================
        // Route::group(['namespace' => 'questions'], function () {
        //     Route::resource('questions', 'QuestionController');
        // });

        //==============================Setting============================
        Route::resource('settings', 'SettingController');
    }
);
