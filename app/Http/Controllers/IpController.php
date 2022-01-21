<?php

namespace App\Http\Controllers;

use App\Models\IP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpController extends Controller
{
    public function index(Request $request)
    {
        $q = null;
        $orderBy = 'desc';
        $sortBy = 'IP';
        $Gtype = null;
        $grp = 1;
        $mask = decbin(ip2long('255.255.255.0'));
        if ($request->has('q')) $q = $request->query('q');
        if ($request->has('orderBy')) {
            $orderBy = $request->query('orderBy');
            if ($orderBy == 'on') $orderBy = 'asc';
            else $orderBy = 'desc';
        }

        if ($q != null && ip2long($q)){
            $q = ip2long($q);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orderBy($sortBy, $orderBy)
                ->get();

        } else if ($q != null && ip2long($q.".0")) {
            $q = ip2long($q.".0");
            $grp = (int)($q / 100);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        } else if ($q != null && ip2long($q.".0.0")) {
            $q = ip2long($q.".0.0");
            $grp = (int)($q / 10000);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        } else if ($q != null && ip2long($q.".0.0.0")) {
            $q = ip2long($q.".0.0.0");
            $grp = (int)($q / 10000000);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }
        else if ($q != null && !ip2long($q) && !ip2long($q.".0") && !ip2long($q.".0.0") && !ip2long($q.".0.0.0")) {
            $q = -1;
            $ip = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }
        else {
            $ip = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }

        return view('searcher', compact('ip', 'q', 'orderBy'));
    }

    /*public function SortIP() {
        $ip = IP::orderBy('ip','DESC')->get();
        return view('searcher', compact('ip'));
    }*/
}
