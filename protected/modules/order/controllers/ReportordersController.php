<?php
class ReportordersController extends Controller
{
  public function actionDownxls()
  {
    // parent::actionDownXLS1();


    if ($_GET['lro'] == 1) {
      $this->RincianPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 2) {
      $this->RekapPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 3) {
      $this->RekapPenjualanPerCustomerXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 4) {
      $this->RekapPenjualanPerSalesXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 5) {
      $this->RekapPenjualanPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 6) {
      $this->RekapPenjualanPerAreaXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 7) {
      $this->RekapPenjualanPerCustomerPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 8) {
      $this->RekapPenjualanPerCustomerPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 9) {
      $this->RekapPenjualanPerSalesPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 10) {
      $this->RekapPenjualanPerSalesPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 11) {
      $this->RekapPenjualanPerAreaPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 12) {
      $this->RekapPenjualanPerAreaPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 13) {
      $this->RincianReturPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 14) {
      $this->RekapReturPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 15) {
      $this->RekapReturPenjualanPerCustomerXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 16) {
      $this->RekapReturPenjualanPerSalesXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 17) {
      $this->RekapReturPenjualanPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 18) {
      $this->RekapReturPenjualanPerAreaXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 19) {
      $this->RekapReturPenjualanPerCustomerPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 20) {
      $this->RekapReturPenjualanPerCustomerPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 21) {
      $this->RekapReturPenjualanPerSalesPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 22) {
      $this->RekapReturPenjualanPerSalesPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 23) {
      $this->RekapReturPenjualanPerAreaPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 24) {
      $this->RekapReturPenjualanPerAreaPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 25) {
      $this->RincianPenjualanReturPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 26) {
      $this->RekapPenjualanReturPenjualanPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 27) {
      $this->RekapPenjualanReturPenjualanPerCustomerXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 28) {
      $this->RekapPenjualanReturPenjualanPerSalesXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 29) {
      $this->RekapPenjualanReturPenjualanPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 30) {
      $this->RekapPenjualanReturPenjualanPerAreaXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 31) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 32) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 33) {
      $this->RekapPenjualanReturPenjualanPerSalesPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 34) {
      $this->RekapPenjualanReturPenjualanPerSalesPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 35) {
      $this->RekapPenjualanReturPenjualanPerAreaPerBarangTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 36) {
      $this->RekapPenjualanReturPenjualanPerAreaPerBarangRincianXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 37) {
      $this->RincianSalesOrderPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 38) {
      $this->RincianSalesOrderOutstandingXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 39) {
      $this->RekapSuratJalanBelumDibuatkanFakturXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 40) {
      $this->RekapPenjualanPerCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 41) {
      $this->RekapReturPenjualanPerCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 42) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 43) {
      $this->RekapPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 44) {
      $this->RekapReturPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 45) {
      $this->RekapPenjualanReturPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 46) {
      $this->RekapTotalPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 47) {
      $this->RekapTotalReturPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 48) {
      $this->RekapTotalPenjualanReturPenjualanPerJenisCustomerPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 49) {
      $this->RekapPenjualanPerBarangPerBulanQtyXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 50) {
      $this->RekapPenjualanPerBarangPerBulanNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 51) {
      $this->RekapPenjualanPerBarangPerBulanQtydanNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 52) {
      $this->RekapPenjualanReturPerCustomerPerBarangPerBulanPerTahunTotalNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 53) {
      $this->RekapPenjualanReturPerCustomerPerBarangPerBulanPerTahunRincianNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 54) {
      $this->RekapPenjualanReturPerCustomerPerBarangPerBulanPerTahunTotalQtyXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 55) {
      $this->RekapPenjualanReturPerCustomerPerBarangPerBulanPerTahunRincianQtyXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 56) {
      $this->RekapSalesOrderOutstandingPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 57) {
      $this->RekapSOPerDokumentBelumStatusMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 58) {
      $this->RekapPenjualanReturPenjualanPerSalesPerBulanPerTahunTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 59) {
      $this->RekapPenjualanReturPenjualanPerSalesPerBarangPerBulanPerTahunTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 60) {
      $this->RekapPenjualanReturPenjualanPerSalesPerCustomerBulanPerTahunTotalXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 61) {
      $this->RekapPenjualanReturAreaCustomerBarangPerBulanPerTahunRincianNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 62) {
      $this->RekapPenjualanReturAreaCustomerBarangPerBulanPerTahunRincianQtyXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 63) {
      $this->LaporanLatLngXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 64) {
      $this->LaporanFotoXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 65) {
      $this->LaporanCustomerBelumAdaKTPXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 66) {
      $this->LaporanCustomerBelumAdaNPWPXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 67) {
      $this->LaporanSudahLengkapLokasiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 68) {
      $this->LaporanSudaFotoXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 69) {
      $this->LaporanCustomerSudahAdaKTPXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 70) {
      $this->LaporanCustomerSudahAdaNPWPXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 71) {
      $this->RekapRealisasiPenjualanPerSalesPerGroupMaterialXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 72) {
      $this->RincianRealisasiPenjualanPerSalesPerGroupMaterialXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 73) {
      $this->RekapPenjualanVSProduksiVSSaldoAkhirXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 74) {
      $this->RekapTTNTPerDokumenStatusBelumMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 75) {
      $this->RekapTTFPerDokumenStatusBelumMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 76) {
      $this->RekapSkalaKomisiPenjualanPerDokumenStatusBelumMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 77) {
      $this->RekapTargetPenjualanPerDokumenStatusBelumMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 78) {
      $this->RekapPerubahanPlafonPerDokumenStatusBelumMaxXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 79) {
      $this->RekapRealisasiPenjualanPerSPVSalesPerGroupMaterialXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 81) {
      $this->LaporanCustomerBelumAdaKategoriXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 82) {
      $this->LaporanCustomerSudahAdaKategoriXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 83) {
      $this->LaporanCustomerBelumAdaGradeXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 84) {
      $this->LaporanCustomerSudahAdaGradeXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 85) {
      $this->LaporanPenjualanReturPenjulanPerCabangPerSalesPerMaterialgroupXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 86) {
      $this->RekapRealisasiPenjualanPerSPVSalesPerGroupMaterial1XLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 87) {
      $this->MonitoringReport1XLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 88) {
      $this->LaporanRekapSalesTargetPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 89) {
      $this->RincianDataCustomerXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 90) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerJenisMaterialPerBulanPerTahunTotalNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 90) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerJenisMaterialPerBulanPerTahunTotalNilai($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 91) {
      $this->RekapSalesOrderOutstandingPerTanggalKirimPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 92) {
      $this->RekapTotalPenjualanReturPenjualanPerJenisMaterialPerKastaPerBulanPerTahunXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 93) {
      $this->RekapPenjualanReturPenjualanPerKastaPerGroupMaterialXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 94) {
      $this->RincianPenjualanReturPerZonaPerSubzonaPerCustomerPerMaterialgroupPerBulanPerTahunNilaiXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 95) {
      $this->RincianPenjualanReturPerZonaPerSubzonaPerCustomerPerMaterialgroupPerBulanPerTahunQtyXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } elseif ($_GET['lro'] == 999) {
      $this->RekapPenjualanReturPenjualanPerCustomerPerJenisMaterialPerBulanPerTahunTotalNilaiCabangXLS($_GET['company'], $_GET['sloc'], $_GET['materialgroup'], $_GET['customer'], $_GET['sales'], $_GET['spvid'], $_GET['product'], $_GET['salesarea'], $_GET['isdisplay'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
    } else {
      echo getCatalog('reportdoesnotexist');
    }
    // echo Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname;
  }

  public function RekapPenjualanPerBarangXLS($companyid, $sloc, $materialgroup, $customer, $employee, $spv, $product, $salesarea, $isdisplay, $startdate, $enddate, $per)
  {
    $this->menuname = 'rekappenjualanperbarang';
    // echo Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname;


    parent::actionDownXLS1();

    $totalnominal1 = 0;
    $totaldisc1 = 0;
    $totalnetto1 = 0;

    // $isdisplay = 0;
    // $employee = '';
    // $customer = '';
    // $product = '';
    // $salesarea = '';
    // $materialgroup = '';
    // $sloc = '';
    // $per = 1;
    // $startdate = '2021-01-01';
    // $enddate = '2021-01-31';
    // $companyid = 1;

    if (isset($spv) && $spv != '') {
      $spvid1 = " and (za.spvid='" . $spv . "' or za.employeeid='" . $spv . "')";
      $spvid2 = " and (c.spvid='" . $spv . "' or c.employeeid='" . $spv . "')";
    } else {
      $spvid1 = "";
      $spvid2 = "";
    }
    if (isset($isdisplay) && $isdisplay != '') {
      $isdisplay1 = " and za.isdisplay = " . $isdisplay . " ";
      $isdisplay2 = " and c.isdisplay = " . $isdisplay . " ";
    } else {
      $isdisplay1 = "";
      $isdisplay2 = "";
    }
    $sql = "select distinct zk.materialgroupid,zk.materialgroupcode,zk.description
				from soheader za 
				join giheader zb on zb.soheaderid = za.soheaderid
				join gidetail zc on zc.giheaderid = zb.giheaderid
				join sodetail zs on zs.sodetailid = zc.sodetailid
				left join employee zd on zd.employeeid = za.employeeid
				join product ze on ze.productid = zs.productid
				left join addressbook zf on zf.addressbookid = za.addressbookid
				left join salesarea zg on zg.salesareaid = zf.salesareaid
				join sloc zh on zh.slocid = zc.slocid
				join invoice zi on zi.giheaderid = zc.giheaderid
				join productplant zj on zj.productid=zc.productid and zj.slocid=zc.slocid
				join materialgroup zk on zk.materialgroupid=zj.materialgroupid
				where zi.recordstatus = 3 and zi.invoiceno is not null and invoiceno not like '%-%-%' and za.companyid = " . $companyid . " and
				zf.fullname like '%" . $customer . "%' and zd.fullname like '%" . $employee . "%' " . $spvid1 . " " . $isdisplay1 . " and ze.productname like '%" . $product . "%' and zg.areaname like '%" . $salesarea . "%' and zk.description like '%" . $materialgroup . "%' and zh.sloccode like '%" . $sloc . "%'
				and zi.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by description";

    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();


    //foreach($dataReader as $row)
    $this->phpSpreadsheet->getActiveSheet()
      ->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
      ->setCellValueByColumnAndRow(3, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
      ->setCellValueByColumnAndRow(6, 1, GetCompanyCode($companyid));
    $line = 4;

    if (isset($spv) && $spv != '') {
      $sql = "SELECT fullname FROM employee WHERE employeeid='" . $spv . "'";
      $fullname = Yii::app()->db->createCommand($sql)->queryScalar();

      $this->phpSpreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(0, $line, 'LAPORAN PER SUPERVISOR')
        ->setCellValueByColumnAndRow(1, $line, ': ' . $fullname);
      $line++;
    }

    foreach ($dataReader as $row) {
      $this->phpSpreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(0, $line, 'Divisi')
        ->setCellValueByColumnAndRow(1, $line, ': ' . $row['description'])
        ->setCellValueByColumnAndRow(2, $line, '')
        ->setCellValueByColumnAndRow(3, $line, '')
        ->setCellValueByColumnAndRow(4, $line, '');

      $line++;
      $this->phpSpreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(0, $line, 'No')
        ->setCellValueByColumnAndRow(1, $line, 'Nama Barang')
        ->setCellValueByColumnAndRow(2, $line, 'Qty')
        ->setCellValueByColumnAndRow(3, $line, 'Price')
        ->setCellValueByColumnAndRow(4, $line, 'Total')
        ->setCellValueByColumnAndRow(5, $line, 'Disc')
        ->setCellValueByColumnAndRow(6, $line, 'Netto');
      $line++;
      $sql1 = "select productid,productname,sum(qty) as giqty,harga,sum(nom) as nominal,sum(nett) as netto from
								(select distinct ss.gidetailid,d.fullname,i.productid,i.productname,k.uomcode,ss.qty,
								(select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid) as harga,
								(ss.qty*(select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid)) as nom,
								(select getamountdiscso(zza.soheaderid,zza.sodetailid,zzb.qty)
								from gidetail zzb 
								join sodetail zza on zza.sodetailid = zzb.sodetailid
								where zzb.giheaderid = b.giheaderid and zzb.productid = i.productid and zzb.gidetailid=ss.gidetailid) as nett
								from invoice a 
								join giheader b on b.giheaderid = a.giheaderid
								join soheader c on c.soheaderid = b.soheaderid
								join addressbook d on d.addressbookid = c.addressbookid
								join employee e on e.employeeid = c.employeeid
								join salesarea f on f.salesareaid = d.salesareaid
								join sodetail g on g.soheaderid = b.soheaderid
								join gidetail ss on ss.giheaderid = b.giheaderid
								join sloc h on h.slocid = ss.slocid
								join product i on i.productid = ss.productid
								join productplant j on j.productid = i.productid and j.slocid=g.slocid
								join unitofmeasure k on k.unitofmeasureid = ss.unitofmeasureid
								where a.recordstatus = 3 and a.invoiceno is not null and invoiceno not like '%-%-%' and
								c.companyid = " . $companyid . " and h.sloccode like '%" . $sloc . "%' and d.fullname like '%" . $customer . "%' and
								e.fullname like '%" . $employee . "%' and f.areaname like '%" . $salesarea . "%' and i.productname like '%" . $product . "%' 
								and j.materialgroupid = " . $row['materialgroupid'] . "
								and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
								and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' " . $spvid2 . " " . $isdisplay2 . "
								)zz group by productid order by productname";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();

      $totalqty = 0;
      $totalnetto = 0;
      $totaldisc = 0;
      $totalnominal = 0;
      $i = 0;

      foreach ($dataReader1 as $row1) {
        $this->phpSpreadsheet->getActiveSheet()
          ->setCellValueByColumnAndRow(0, $line, $i += 1)
          ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
          ->setCellValueByColumnAndRow(2, $line, $row1['giqty'])
          ->setCellValueByColumnAndRow(3, $line, $row1['harga'] / $per)
          ->setCellValueByColumnAndRow(4, $line, $row1['nominal'] / $per)
          ->setCellValueByColumnAndRow(5, $line, ($row1['nominal'] / $per) - ($row1['netto'] / $per))
          ->setCellValueByColumnAndRow(6, $line, $row1['netto'] / $per);
        $line++;
        $totalqty += $row1['giqty'];
        $totalnominal += $row1['nominal'] / $per;
        $totaldisc += ($row1['nominal'] / $per) - ($row1['netto'] / $per);
        $totalnetto += $row1['netto'] / $per;
      }
      $this->phpSpreadsheet->getActiveSheet()
        ->setCellValueByColumnAndRow(1, $line, 'Total ' . $row['description'])
        ->setCellValueByColumnAndRow(2, $line, $totalqty)
        ->setCellValueByColumnAndRow(4, $line, $totalnominal)
        ->setCellValueByColumnAndRow(5, $line, $totaldisc)
        ->setCellValueByColumnAndRow(6, $line, $totalnetto);
      $line += 2;
      $totalnominal1 += $totalnominal;
      $totaldisc1 += $totaldisc;
      $totalnetto1 += $totalnetto;
    }
    $this->phpSpreadsheet->getActiveSheet()
      ->setCellValueByColumnAndRow(1, $line, 'TOTAL')
      ->setCellValueByColumnAndRow(4, $line, $totalnominal1)
      ->setCellValueByColumnAndRow(5, $line, $totaldisc1)
      ->setCellValueByColumnAndRow(6, $line, $totalnetto1);
    $line += 2;

    $this->savePhpSpreadsheet($this->phpSpreadsheet);
  }
}
