<?php

namespace App\Http\Services;

use App\Config\ps_constant;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Modules\Core\Entities\Utilities\ActivatedFileName;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\SystemCode;

class PsCoreService
{
    function checkPermission($eStIAHSn = null, $mFDcAZ0D = null, $xy3uhd5n = null, $a9_IFWaP = null)
    {
        goto uPUN4WIJ;
        bDcdl6gh:
        $a9_IFWaP = __("no_permission");
        goto o3CWQ9M9;
        o3CWQ9M9:
        Ya4H9red:
        goto Y0YXHAGb;
        uPUN4WIJ:
        if (!($a9_IFWaP == null)) {
            goto Ya4H9red;
        }
        goto bDcdl6gh;
        Y0YXHAGb:
        if (!Gate::denies($eStIAHSn, $mFDcAZ0D)) {
            goto ZADifxHX;
        }
        goto nWuhYz25;
        nWuhYz25:
        return redirectView($xy3uhd5n, $a9_IFWaP, "danger");
        goto iPgDybnS;
        iPgDybnS:
        ZADifxHX:
        goto eyqF93gy;
        eyqF93gy:
    }
    public function getSystemCode()
    {
        goto XebVvU8g;
        HSINo1Hf:
        $Y9Lj7w62 = explode("/n", base64_decode($IjGG1wnz->code));
        goto nPsEGsO2;
        ZudUDBi5:
        $F5CyMlg4->update();
        goto x_W13bVd;
        vlKtctp_:
        $wwrE6Jrw = base64_decode($WD7AJAV4->code);
        goto s9V3iWPi;
        QiTe_Psb:
        $W3sQZTxj = explode("/n", base64_decode($WD7AJAV4->code));
        goto A_QEGSmn;
        W2BD30LP:
        yiMgDPE9:
        goto jQV0Q0pV;
        s9V3iWPi:
        $AedVbW8f = Carbon::now();
        goto ZsYPrW5K;
        x_W13bVd:
        ReOyJ9vZ:
        goto A5Nt4rY8;
        A3ScLXwF:
        $F5CyMlg4 = SystemCode::first();
        goto Kzegc7RI;
        ZsYPrW5K:
        $MvJPj48p = Carbon::now()->addDays(ps_constant::freeTrialTotalDay);
        goto A3ScLXwF;
        tE5AhnA9:
        return $Mwh7svRn;
        goto W2BD30LP;
        A5Nt4rY8:
        $IjGG1wnz = SystemCode::first();
        goto HSINo1Hf;
        VnVdNmhy:
        if (empty($WD7AJAV4)) {
            goto yiMgDPE9;
        }
        goto QiTe_Psb;
        XebVvU8g:
        $WD7AJAV4 = SystemCode::first();
        goto VnVdNmhy;
        nPsEGsO2:
        $KVvWHXtR = date_create($Y9Lj7w62[2]);
        goto e3WbgFvi;
        A_QEGSmn:
        if (!empty($W3sQZTxj[2])) {
            goto ReOyJ9vZ;
        }
        goto vlKtctp_;
        e3WbgFvi:
        $Mwh7svRn = date_format($KVvWHXtR, "M d, Y h:i:s");
        goto tE5AhnA9;
        Kzegc7RI:
        $F5CyMlg4->code = base64_encode($wwrE6Jrw . $AedVbW8f . "/n" . $MvJPj48p);
        goto ZudUDBi5;
        jQV0Q0pV:
    }
    public function updateLicense($CV2oJAK_)
    {
        goto BF0DR3ta;
        Dx_xHzu_:
        goto OsbvSTaR;
        goto DVukBqZb;
        VmS2zD11:
        if (empty($P2BrLOiW)) {
            goto j1o8CW2s;
        }
        goto i99e5JF1;
        zItaulQn:
        $P2BrLOiW->added_user_id = 1;
        goto U4NKqytR;
        PiXdZv2R:
        pRiq_JYG:
        goto tX_2vVhr;
        tX_2vVhr:
        $s9P3FbmH = checkPurchasedCode($vhW3O44o);
        goto mfBCjrQ1;
        vxmBclgn:
        $P2BrLOiW = new Project();
        goto zEoBicSt;
        xDzS_h92:
        $P2BrLOiW->project_code = $CV2oJAK_->purchased_code;
        goto OW6f_18E;
        RCrA29GB:
        $sEEHoYND = $CV2oJAK_->purchased_code;
        goto StPju6Ol;
        wy1fFoP0:
        v_vHfz0G:
        goto RCrA29GB;
        VMpIe8W9:
        B2wi4Hy3:
        goto BCXp3EbI;
        cRYDIOGV:
        if ($sEEHoYND == "00000000-0000-0000-0000-000000000000") {
            goto B2wi4Hy3;
        }
        goto FN4820JP;
        onMtdjax:
        if (App::environment("production")) {
            goto v_vHfz0G;
        }
        goto cNOKsuns;
        EHbwrDT2:
        $P2BrLOiW->update();
        goto Dx_xHzu_;
        LNbFzTHw:
        FHhoNN0s:
        goto OqBYzwBY;
        i99e5JF1:
        $P2BrLOiW->project_code = $CV2oJAK_->purchased_code;
        goto kbcTg485;
        MNLDz4yx:
        goto pRiq_JYG;
        goto wy1fFoP0;
        BF0DR3ta:
        if (config('app.development')) {
            goto OhVzpGLf;
        }
        goto onMtdjax;
        StPju6Ol:
        $vhW3O44o = json_decode(Http::withHeaders(['Authorization' => config('app.envato_token_type') . " " . config('app.envato_token')])->get(ps_constant::envatoApiUri . ps_constant::envatoApiVersion . '/market/author/sale?code=' . $sEEHoYND));
        goto PiXdZv2R;
        q9ekOPb9:
        g0Q8KU2L:
        goto MNLDz4yx;
        BCXp3EbI:
        $vhW3O44o = json_decode(file_get_contents(public_path('json/buyser-purchase-response.json'), true));
        goto q9ekOPb9;
        OqBYzwBY:
        OhVzpGLf:
        goto uJ9pzMgQ;
        snx5Kmw9:
        OsbvSTaR:
        goto VTu_3H0J;
        zEoBicSt:
        $P2BrLOiW->project_name = "default";
        goto xDzS_h92;
        FN4820JP:
        $vhW3O44o = "";
        goto c8auH4Am;
        U4NKqytR:
        $P2BrLOiW->save();
        goto snx5Kmw9;
        mfBCjrQ1:
        if (empty($s9P3FbmH)) {
            goto FHhoNN0s;
        }
        goto QnTJKKW4;
        c8auH4Am:
        goto g0Q8KU2L;
        goto VMpIe8W9;
        uJ9pzMgQ:
        $P2BrLOiW = Project::first();
        goto VmS2zD11;
        DVukBqZb:
        j1o8CW2s:
        goto vxmBclgn;
        OW6f_18E:
        $P2BrLOiW->project_url = $CV2oJAK_->backend_url;
        goto zItaulQn;
        kbcTg485:
        $P2BrLOiW->project_url = $CV2oJAK_->backend_url;
        goto EHbwrDT2;
        QnTJKKW4:
        return $s9P3FbmH;
        goto LNbFzTHw;
        cNOKsuns:
        $sEEHoYND = $CV2oJAK_->purchased_code;
        goto cRYDIOGV;
        VTu_3H0J:
    }
    public function activateLicense($CV2oJAK_)
    {
        goto BnaMn9DS;
        LyVsZ3QR:
        $CxWe46nB = file_get_contents($li0JEZrY);
        goto ctXOPjea;
        V0l0spCS:
        $ls6IPBSJ->status = "success";
        goto QluIx2Zs;
        W8rO5PzA:
        d4dH0s8n:
        goto K0ySTBXD;
        T_NxfS2a:
        AJzuBr3z:
        goto hDtkrbNV;
        tVxtXxl_:
        return $IoJUeMpC;
        goto faVdImVU;
        EIJGKfiE:
        $GiEZY0Jh->file_name = $li0JEZrY;
        goto wTJGAM0b;
        LFAHoGHA:
        $qmqBC2jf = $CV2oJAK_->file("zipFile");
        goto thAAO2EI;
        U1yYihLy:
        $GiEZY0Jh = new ActivatedFileName();
        goto EIJGKfiE;
        AWVou1Bw:
        goto skYuzRzs;
        goto CmLebOmm;
        Jwu1DE2W:
        $jeGThxZ_ = explode("\n", base64_decode($eOdSHZV2));
        goto h3Cp1FX_;
        Ta8YnyUD:
        goto snmyRpmi;
        goto SbfkLSVd;
        EMvz83h5:
        $LmTILyZC[] = $fYfdZ9Vb;
        goto V6KLNhz5;
        UlfrpxWK:
        $P2BrLOiW->update();
        goto U1yYihLy;
        laaJu43l:
        $KvL2Ruof->message = __('domain_verify_success');
        goto XlSoMMFr;
        iMtddyHH:
        $Y6k2Fo2j->open($qmqBC2jf);
        goto tZmlwNCy;
        Dj_8FGxV:
        $KvL2Ruof = new \stdClass();
        goto UQHdQIYx;
        t4ZVU6UH:
        $TokDL3Mi = $jeGThxZ_[2];
        goto cYKzyEJx;
        zRxqCGpO:
        $GiEZY0Jh->save();
        goto xGLns54Y;
        Q1ziLsXW:
        $eOdSHZV2 = $Kdci2Ni9->key;
        goto T8DTksRj;
        ML2fMkfy:
        $ls6IPBSJ->message = __('license_verify_fail');
        goto zxAiHk1T;
        pOUo5NGL:
        $LmTILyZC[] = $ls6IPBSJ;
        goto Ta8YnyUD;
        OmWNCKUk:
        ++$sFYwb81z;
        goto W8rO5PzA;
        V6KLNhz5:
        ++$sFYwb81z;
        goto T_NxfS2a;
        Mn0o00Af:
        $KvL2Ruof->status = "success";
        goto laaJu43l;
        T8DTksRj:
        $P2BrLOiW = $Kdci2Ni9->project;
        goto Jwu1DE2W;
        oV0rY3RC:
        if ($GcoxKnFi !== $Ioy1uQLP->project_url) {
            goto vT3G4iZP;
        }
        goto SLK9Mphc;
        N6EHqf5m:
        $P2BrLOiW = Project::first();
        goto qJLxxelj;
        VDP4NWL7:
        goto AJzuBr3z;
        goto h2Q8GKdg;
        qUiLqvQk:
        $fYfdZ9Vb->status = "danger";
        goto TM6102Xf;
        xGLns54Y:
        r5ig92Sd:
        goto qB25Wmct;
        tZmlwNCy:
        $li0JEZrY = $Y6k2Fo2j->getNameIndex(0);
        goto AWDu7_vn;
        pettzaUt:
        $fYfdZ9Vb->status = "success";
        goto HCZOPE52;
        pP9C5AIx:
        if ($P2BrLOiW->base_project_id !== $Ioy1uQLP->base_project_id) {
            goto t6lRVJ5m;
        }
        goto txmFbhZX;
        thAAO2EI:
        $Y6k2Fo2j = new \ZipArchive();
        goto iMtddyHH;
        fOQsX53A:
        $sFYwb81z = 0;
        goto oV0rY3RC;
        UQHdQIYx:
        $KvL2Ruof->status = "danger";
        goto EiArF9cs;
        SbfkLSVd:
        Bnq2X8cm:
        goto IhxHLkyz;
        IhxHLkyz:
        $ls6IPBSJ = new \stdClass();
        goto WHZH4AeY;
        XlSoMMFr:
        $LmTILyZC[] = $KvL2Ruof;
        goto l0hUjtvR;
        CmLebOmm:
        fPWrLvDr:
        goto LFAHoGHA;
        VPAcHaG6:
        snmyRpmi:
        goto pP9C5AIx;
        K0ySTBXD:
        if ($STaRChRC !== $Ioy1uQLP->project_code) {
            goto Bnq2X8cm;
        }
        goto zxdoF3EK;
        zxAiHk1T:
        $LmTILyZC[] = $ls6IPBSJ;
        goto pEcnCvyz;
        HCZOPE52:
        $fYfdZ9Vb->message = __('base_proj_same');
        goto L1jD1ycn;
        nrt573oy:
        vT3G4iZP:
        goto Dj_8FGxV;
        L1jD1ycn:
        $LmTILyZC[] = $fYfdZ9Vb;
        goto VDP4NWL7;
        zxdoF3EK:
        $ls6IPBSJ = new \stdClass();
        goto V0l0spCS;
        qJLxxelj:
        $P2BrLOiW->ps_license_code = $TokDL3Mi;
        goto UlfrpxWK;
        CgOG_dOd:
        $LmTILyZC[] = $KvL2Ruof;
        goto OmWNCKUk;
        hDtkrbNV:
        if (!empty($sFYwb81z)) {
            goto r5ig92Sd;
        }
        goto N6EHqf5m;
        y_qNGuuG:
        $Y6k2Fo2j->close();
        goto uwkEfIzK;
        qB25Wmct:
        $IoJUeMpC = ["logMessages" => $LmTILyZC, "hasError" => $sFYwb81z, "zipFileName" => $li0JEZrY];
        goto tVxtXxl_;
        IUfZ2CrU:
        $fYfdZ9Vb = new \stdClass();
        goto qUiLqvQk;
        cYKzyEJx:
        $LmTILyZC = [];
        goto MgdrM5UQ;
        h3Cp1FX_:
        $GcoxKnFi = $jeGThxZ_[0];
        goto H75piElg;
        l0hUjtvR:
        goto d4dH0s8n;
        goto nrt573oy;
        sAs_nIPy:
        $li0JEZrY = str_replace("zip", "json", $CV2oJAK_->zipFile);
        goto AWVou1Bw;
        H75piElg:
        $STaRChRC = $jeGThxZ_[1];
        goto t4ZVU6UH;
        SLK9Mphc:
        $KvL2Ruof = new \stdClass();
        goto Mn0o00Af;
        h2Q8GKdg:
        t6lRVJ5m:
        goto IUfZ2CrU;
        u0MUbV8b:
        if ($CV2oJAK_->hasFile("zipFile")) {
            goto fPWrLvDr;
        }
        goto sAs_nIPy;
        TM6102Xf:
        $fYfdZ9Vb->message = __('base_proj_not_same');
        goto EMvz83h5;
        MgdrM5UQ:
        $Ioy1uQLP = Project::first();
        goto fOQsX53A;
        AWDu7_vn:
        $Y6k2Fo2j->extractTo("./");
        goto y_qNGuuG;
        EiArF9cs:
        $KvL2Ruof->message = __('domain_verify_fail');
        goto CgOG_dOd;
        BnaMn9DS:
        config('app.key', $CV2oJAK_->key);
        goto u0MUbV8b;
        WHZH4AeY:
        $ls6IPBSJ->status = "danger";
        goto ML2fMkfy;
        uwkEfIzK:
        skYuzRzs:
        goto LyVsZ3QR;
        ctXOPjea:
        $Kdci2Ni9 = json_decode($CxWe46nB);
        goto Q1ziLsXW;
        txmFbhZX:
        $fYfdZ9Vb = new \stdClass();
        goto pettzaUt;
        pEcnCvyz:
        ++$sFYwb81z;
        goto VPAcHaG6;
        QluIx2Zs:
        $ls6IPBSJ->message = __('license_verify_success');
        goto pOUo5NGL;
        wTJGAM0b:
        $GiEZY0Jh->is_imported = 0;
        goto zRxqCGpO;
        faVdImVU:
    }
    public function activateLicenseFromBuilder($Kdci2Ni9)
    {
        goto R_spTQ5g;
        oJgS3AtQ:
        $P2BrLOiW->update();
        goto LLm_2s09;
        p2EQcfHE:
        $fYfdZ9Vb->message = __('base_proj_not_same');
        goto pgmL9Ca_;
        RTXTLl68:
        MBz3JDIZ:
        goto mTAEHtYq;
        as8JHWn8:
        YBGfjFJZ:
        goto VdoQVzl_;
        ddii7B13:
        ++$sFYwb81z;
        goto as8JHWn8;
        DmHgmqF_:
        $LmTILyZC[] = $KvL2Ruof;
        goto snIv6PFt;
        zN0TwrCD:
        $ls6IPBSJ = new \stdClass();
        goto s4xnbXOu;
        ipFFlSS7:
        $KvL2Ruof->status = "success";
        goto LS71N8EV;
        dOBpgXKp:
        $KvL2Ruof = new \stdClass();
        goto ipFFlSS7;
        BMCDpMKm:
        $fYfdZ9Vb->status = "danger";
        goto p2EQcfHE;
        PbdgtCAP:
        $P2BrLOiW = Project::first();
        goto KNHUxhVl;
        Ru1NogIZ:
        goto iFmPHSmd;
        goto qzA846n5;
        VdoQVzl_:
        if ($P2BrLOiW->base_project_id !== $Ioy1uQLP->base_project_id) {
            goto G1gKJOYG;
        }
        goto lL0WtwZN;
        wrl3HXGk:
        $fYfdZ9Vb->message = __('base_proj_same');
        goto gKj_3tUP;
        KantoECm:
        $eOdSHZV2 = $Kdci2Ni9->key;
        goto sb9SZWdF;
        d34k_v0F:
        $ls6IPBSJ->message = __('license_verify_success');
        goto KUA6DW_0;
        JTelOfPl:
        iFmPHSmd:
        goto R1RyiSGx;
        kOFNndTV:
        $LmTILyZC = [];
        goto ab0aW76Q;
        cXUyXDJX:
        $ls6IPBSJ->message = __('license_verify_fail');
        goto l3DFtR2U;
        L0tx6cMt:
        ++$sFYwb81z;
        goto Xay5itAR;
        uuvtR8Dk:
        $KvL2Ruof->message = __('domain_verify_fail');
        goto Z0JSfUGs;
        LS71N8EV:
        $KvL2Ruof->message = __('domain_verify_success');
        goto DmHgmqF_;
        Y3D30q43:
        $KvL2Ruof = new \stdClass();
        goto eizCFANs;
        pSrAt3DZ:
        $ls6IPBSJ = new \stdClass();
        goto CnlJsiS4;
        Z0JSfUGs:
        $LmTILyZC[] = $KvL2Ruof;
        goto L0tx6cMt;
        LLm_2s09:
        $GiEZY0Jh = new ActivatedFileName();
        goto uDC0c25A;
        KNHUxhVl:
        $P2BrLOiW->ps_license_code = $TokDL3Mi;
        goto oJgS3AtQ;
        s4xnbXOu:
        $ls6IPBSJ->status = "success";
        goto d34k_v0F;
        IDWolAj_:
        $jeGThxZ_ = explode("\n", base64_decode($eOdSHZV2));
        goto C2qzPABv;
        o7pN9K2d:
        $TokDL3Mi = $jeGThxZ_[2];
        goto kOFNndTV;
        V3Ajf2Vn:
        if ($GcoxKnFi !== $Ioy1uQLP->project_url) {
            goto KgnJY7JM;
        }
        goto dOBpgXKp;
        pgmL9Ca_:
        $LmTILyZC[] = $fYfdZ9Vb;
        goto EryFY5Rf;
        mTAEHtYq:
        $IoJUeMpC = ["logMessages" => $LmTILyZC, "hasError" => $sFYwb81z, "activateStatus" => $sFYwb81z == 0 ? 'true' : 'false'];
        goto WPy2WbIn;
        zhqNk16b:
        $fYfdZ9Vb = new \stdClass();
        goto BMCDpMKm;
        eizCFANs:
        $KvL2Ruof->status = "danger";
        goto uuvtR8Dk;
        R1RyiSGx:
        if (!empty($sFYwb81z)) {
            goto MBz3JDIZ;
        }
        goto PbdgtCAP;
        Yyc1QXP6:
        $STaRChRC = $jeGThxZ_[1];
        goto o7pN9K2d;
        ab0aW76Q:
        $Ioy1uQLP = Project::first();
        goto Ip1e14os;
        EryFY5Rf:
        ++$sFYwb81z;
        goto JTelOfPl;
        WPy2WbIn:
        return $IoJUeMpC;
        goto EOe5Tzt8;
        uDC0c25A:
        $GiEZY0Jh->file_name = 'API';
        goto picfksK_;
        qzA846n5:
        G1gKJOYG:
        goto zhqNk16b;
        l3DFtR2U:
        $LmTILyZC[] = $ls6IPBSJ;
        goto ddii7B13;
        lL0WtwZN:
        $fYfdZ9Vb = new \stdClass();
        goto PbR4j7Ze;
        picfksK_:
        $GiEZY0Jh->is_imported = 0;
        goto dExhS3mG;
        KUA6DW_0:
        $LmTILyZC[] = $ls6IPBSJ;
        goto SacoFL2r;
        Qvg4WP__:
        if ($STaRChRC !== $Ioy1uQLP->project_code) {
            goto EiIOB9rj;
        }
        goto zN0TwrCD;
        sGgPwbxd:
        EiIOB9rj:
        goto pSrAt3DZ;
        Xay5itAR:
        gJ3ywBYc:
        goto Qvg4WP__;
        R_spTQ5g:
        config('app.key', $Kdci2Ni9->key);
        goto KantoECm;
        PbR4j7Ze:
        $fYfdZ9Vb->status = "success";
        goto wrl3HXGk;
        Ip1e14os:
        $sFYwb81z = 0;
        goto V3Ajf2Vn;
        CnlJsiS4:
        $ls6IPBSJ->status = "danger";
        goto cXUyXDJX;
        ZeX7U9eN:
        KgnJY7JM:
        goto Y3D30q43;
        snIv6PFt:
        goto gJ3ywBYc;
        goto ZeX7U9eN;
        sb9SZWdF:
        $P2BrLOiW = $Kdci2Ni9->project;
        goto IDWolAj_;
        C2qzPABv:
        $GcoxKnFi = $jeGThxZ_[0];
        goto Yyc1QXP6;
        dExhS3mG:
        $GiEZY0Jh->save();
        goto RTXTLl68;
        gKj_3tUP:
        $LmTILyZC[] = $fYfdZ9Vb;
        goto Ru1NogIZ;
        SacoFL2r:
        goto YBGfjFJZ;
        goto sGgPwbxd;
        EOe5Tzt8:
    }
}
