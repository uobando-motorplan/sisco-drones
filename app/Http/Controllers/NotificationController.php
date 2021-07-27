<?php

namespace App\Http\Controllers;

use App\Notifications\ClosedQuotationNotification;
use App\Quotation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    /**
     *
     * Class Constructor 
     *
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['closed_quotation', 'sale_confirmed']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notifications.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DatabaseNotification::find($id)->delete();

        return redirect()->back()
            ->with('success', 'El registro fue eliminado correctamente.');
    }

    /**
     *
     * Return a listing of the resource for datatables
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {
        $notifications = auth()->user()->notifications;

        return DataTables::of($notifications)
            ->addColumn('title', function ($notification) {
                return $notification->data['title'];
            })
            ->addColumn('text', function ($notification) {
            	return $notification->data['text'];
            })
            ->editColumn('read_at', function ($notification) {
                return $notification->read_at ? Carbon::parse($notification->read_at)->format('Y-m-d H:i:s') : '';
            })
            ->editColumn('created_at', function ($notification) {
                return $notification->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('actions', function ($notification) {
                return view('notifications/actions', compact('notification'));
            })
            ->rawColumns(['title', 'status', 'actions'])
            ->make(true);
    }

    /**
     *
     * Marca como leidas a todas las notificaciones no leidas del usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function readAll()
    {
        foreach (auth()->user()->unreadNotifications as $unreadNotifications) {
            $unreadNotifications->markAsRead();
        }
    }
}
