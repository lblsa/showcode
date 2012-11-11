//-----------------------------------------------------------------------------------
// FILENAME: ScanEventForm.cs
// Copyright � 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: ����� �������� �� ������������ QR-���� �����������. 
// ----------------------------------------------------------------------------------
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Runtime.InteropServices;
using System.Net;

namespace SQScanner
{
    /// <summary>
    /// ����� ����� ������������ �����������.
    /// </summary>
    public partial class ScanEventForm : Form
    {
        /// ��������� ��������� ��� �������������� ������������
        const string STATUS_MINUS_1 = "��������� ����� �� ����������";                             // statusCode = -1
        const string STATUS_MINUS_2 = "�������� �������� ���������� � ��������";                   // statusCode = -2
        const string STATUS_MINUS_3 = "��������� ����������� �� ����������";                       // statusCode = -3
        //const string STATUS_0 = "����� ������������� ������� ������� �����������";                 // statusCode = 0
        const string STATUS_1 = "QR-��� ����������� ������";                                       // statusCode = 1
        //const string STATUS_2 = "����� ������������, �� �� �������";                               // statusCode = 2
        //const string STATUS_3 = "����� ������� � ������������";                                    // statusCode = 3
        //const string STATUS_4 = "����� �� �������, ����� ����������";                              // statusCode = 4
        //const string STATUS_5 = "����� ��� �����������";                                           // statusCode = 5
        //const string STATUS_6 = "����� ������� �� ���������";                                      // statusCode = 6

        const string STATUS_WRONG_FORMAT = "�������� ������ QR-���� �����������!";
        const string STATUS_WRONG_EVENT_CODE = "Data is not correct";

        const string HTTP_REQUEST_ADDRESS_PART1 = "http://showcode.ru/";
        //const string HTTP_REQUEST_ADDRESS_PART2 = "events/webApi?ticket=";
        //const string HTTP_REQUEST_ADDRESS_PART4 = "&event=";
        //const string HTTP_REQUEST_ADDRESS_TICKET_VIEW = "http://showcode.ru/ticket/view/";
        const string HTTP_REQUEST_GET_EVENT_PART1 = "events/webApi?event=";
        const string HTTP_REQUEST_GET_EVENT_PART2 = "&name=1";
        //const string HTTP_REQUEST_GET_EVENT_PART3 = "&count=1";
        //const string HTTP_REQUEST_MARK_TICKET = "&markticket=1";
        //const string HTTP_REQUEST_DONT_MARK_TICKET = "&markticket=0";

        const int QR_EVENT_LENGTH = 39;
        const int QR_EVENT_API_LENGTH = 7;
        const string QR_EVENT_API_NAME = "apikey:";
        //const int QR_TICKET_LENGTH = 43;
        //const int QR_TICKET_API_LENGTH = 31;

        // ������� ������� ���� ������������ ������� � �������� ��� ����, ����� ����� 
        // ���� ��������� �� ��� ����� �� ������ ����� ��� �� ��������.
        private MainForm _mainForm;
        private Settings _settingsForm;

        private API ticketReader = null;

        private EventHandler myReadNotifyHandler = null;
        private EventHandler myStatusNotifyHandler = null;

        public string ourEventCode = "";    // 32-���������� ������ � ����� �����������
        public string ourEventName = "";    // �������� �����������

        /// <summary>
        /// ���� ��������� ����������� ��������������� �� ������ ������ ��� ���.
        /// </summary>
        private bool isReaderInitiated = false;

        /// <summary>
        /// ����� ���������� ��� �������� ����� � �������������� Reader.
        /// </summary>
        private void ScanEventForm_Load(object sender, EventArgs e)
        {
            // ������� � �������������� ������ ������� �����-�����.
            this.ticketReader = new API();
            this.isReaderInitiated = this.ticketReader.InitReader();

            if (!(this.isReaderInitiated))// ���� ������ Reader'a �� ��� ���������������...
            {
                // ���������� ��������� �� ������ � ��������� ����������.
                MessageBox.Show("��������� ������ ������������� Reader'a!");
                Application.Exit();
            }
            else // ���� ������ Reader'a ��� ���������������...
            {
                // ������� ���������� ����������� � ������� Reader'a.
                this.myStatusNotifyHandler = new EventHandler(myReader_StatusNotify);
                ticketReader.AttachStatusNotify(myStatusNotifyHandler);
            }

            // ��������� � ����� �������� QR-���� ����������� ��� ������������ � ������� ���������� ����������� �� ��������������� QR-�����.
            this.ticketReader.StartRead(true);
            this.myReadNotifyHandler = new EventHandler(myReader_ReadNotify);
            this.ticketReader.AttachReadNotify(myReadNotifyHandler);
            return;
        }

        /// <summary>
        /// ����� ���������� ��� �������� ����� ����������.
        /// </summary>
        private void ScanEventForm_Closing(object sender, CancelEventArgs e)
        {
            if (this.isReaderInitiated)
            {
                // ������� �����
                this.ticketReader.DetachReadNotify();
                this.ticketReader.DetachStatusNotify();
                this.ticketReader.TermReader();
            }
            return;
        }

        /// <summary>
        /// ���������� ����������� � ������� ����������� �����-�����.
        /// </summary>
        private void myReader_StatusNotify(object Sender, EventArgs e)
        {
            // Checks if the Invoke method is required because the StatusNotify delegate is called by a different thread
            if (this.InvokeRequired)
            {
                // Executes the StatusNotify delegate on the main thread
                this.Invoke(myStatusNotifyHandler, new object[] { Sender, e });
            }
            else
            {
                // �������� ������ Reader'a
                Symbol.Barcode.BarcodeStatus TheStatusData = this.ticketReader.Reader.GetNextStatus();

                switch (TheStatusData.State)
                {
                    case Symbol.Barcode.States.WAITING:
                        this.ticketReader.StopRead();
                        this.ticketReader.StartRead(true);
                        break;

                    case Symbol.Barcode.States.IDLE:
                        
                        break;

                    case Symbol.Barcode.States.READY:
                        
                        break;

                    default:
                        this.labelInfo.Text = TheStatusData.Text;
                        break;
                }
            }
        }

        /// <summary>
        /// ����� ��������� ����������� � ��������� Reader'a.
        /// </summary>
        private void myReader_ReadNotify(object sender, EventArgs e)
        {
            // Checks if the Invoke method is required because the ReadNotify delegate is called by a different thread
            if (this.InvokeRequired)
            {
                // Executes the ReadNotify delegate on the main thread
                this.Invoke(myReadNotifyHandler, new object[] { sender, e });
            }
            else
            {
                // Get ReaderData
                Symbol.Barcode.ReaderData TheReaderData = this.ticketReader.Reader.GetNextReaderData();

                switch (TheReaderData.Result)
                {
                    case Symbol.Results.SUCCESS:
                        // ������������ ����������� ������ ������ � ��������� ������ ��������� �����.
                        HandleData(TheReaderData);
                        System.Threading.Thread.Sleep(1000);
                        this.ticketReader.StartRead(true);
                        break;

                    case Symbol.Results.E_SCN_READTIMEOUT:
                        this.ticketReader.StartRead(true);
                        break;

                    case Symbol.Results.CANCELED:
                        break;

                    case Symbol.Results.E_SCN_DEVICEFAILURE:
                        this.ticketReader.StopRead();
                        this.ticketReader.StartRead(true);
                        break;

                    default:
                        string sMsg = "�� ������� ��������� ��� �����������!\n"
                            + "����� QR-���� = "
                            + (TheReaderData.Result).ToString();
                        MessageBox.Show(sMsg, "�������� ������!");

                        if (TheReaderData.Result == Symbol.Results.E_SCN_READINCOMPATIBLE)
                        {
                            // If the failure is E_SCN_READINCOMPATIBLE, exit the application.
                            MessageBox.Show("�������� ����������� ������! ���������� ����� ���������.", "����������� ������");
                            this.Close();
                            return;
                        }

                        break;
                }
            }
        }

        /// <summary>
        /// ����� ������ ������ ��������� ��������� � ���������� ��������� ������������ �����������.
        /// </summary>
        /// <param name="statusCode">���������� ���� ������� ������������ QR-����</param>
        private void setStatusCodeWithAlert(int statusCode)
        {
            string message;
            switch (statusCode)
            {
                case -3: message = STATUS_MINUS_3; this.labelMain.ForeColor = Color.Black; this.buttonOK.Enabled = false; break;
                case -2: message = STATUS_MINUS_2; this.labelMain.ForeColor = Color.Black; this.buttonOK.Enabled = false; break;
                case -1: message = STATUS_MINUS_1; this.labelMain.ForeColor = Color.Black; this.buttonOK.Enabled = false; break;
                case 1: message = STATUS_1; this.labelMain.ForeColor = Color.LightGreen; this.buttonOK.Enabled = true; break;
                default: message = STATUS_MINUS_1; this.labelMain.ForeColor = Color.Black; this.buttonOK.Enabled = false; break;
            }
            this.labelInfo.Text = message;
            this.labelMain.Text = this.ourEventName;
        }

        /// <summary>
        /// ����� ��������� ������, ����������� � ���������� ������������.
        /// </summary>
        private void HandleData(Symbol.Barcode.ReaderData myReaderDataObject)
        {
            // ��������� ��� ����������� �� ������������ ��������� �������
            bool eventIsProper = false;
            if (myReaderDataObject.Text.Length == QR_EVENT_LENGTH)
                if (myReaderDataObject.Text.Substring(0, QR_EVENT_API_LENGTH) == QR_EVENT_API_NAME)
                    eventIsProper = true;
            
            if (eventIsProper)
            {
                // ���� �� ������ �� ������� � ���������� �����������
                // ��������� �� ������������� QR-���� ������������� �����������
                this.ourEventCode = myReaderDataObject.Text.Substring(QR_EVENT_API_LENGTH, QR_EVENT_LENGTH - QR_EVENT_API_LENGTH);

                // ���������� ������ �������, � ���� �����������
                string requestString = HTTP_REQUEST_ADDRESS_PART1 + HTTP_REQUEST_GET_EVENT_PART1 + this.ourEventCode + HTTP_REQUEST_GET_EVENT_PART2;

                // ���������� � ������� � ����� � ���� ����� � �������� �����������
                string serverResponseString;
                // �������� ����� �������
                try
                {
                    HttpWebRequest httpRequest = (HttpWebRequest)WebRequest.Create(requestString);
                    //httpRequest.Credentials = CredentialCache.DefaultCredentials;
                    HttpWebResponse httpResponse = (HttpWebResponse)httpRequest.GetResponse();
                    System.IO.Stream dataStream = httpResponse.GetResponseStream();
                    System.IO.StreamReader streamReader = new System.IO.StreamReader(dataStream);
                    serverResponseString = streamReader.ReadToEnd();
                    streamReader.Close();
                    httpResponse.Close();
                }
                catch (Exception e)
                {
                    serverResponseString = "";
                    return;
                }

                int returnCode;
                if (serverResponseString == "")
                    returnCode = -2;
                else
                {
                    try
                    {
                        if (serverResponseString != STATUS_WRONG_EVENT_CODE)
                        {
                            returnCode = 1;
                            this.ourEventName = serverResponseString;
                        }
                        else
                        {
                            returnCode = -3;
                        }
                    }
                    catch (Exception e)
                    {
                        returnCode = -1;
                    }
                }
                setStatusCodeWithAlert(returnCode);
            }
            else
            {
                // ������ QR-���� �� ������������� ������� �����������
                this.labelInfo.Text = STATUS_WRONG_FORMAT;
            }
        }

        /// <summary>
        /// ����������� ����������� ������ ScanEventForm.
        /// </summary>
        public ScanEventForm()
        {
            InitializeComponent();
        }

        /// <summary>
        /// ������������� ����������� ������ ScanEventForm.
        /// </summary>
        /// <param name="f1">���������� ���� MainForm ��� ����� � ������� ������ ����������.</param>
        /// <param name="f2">���������� ���� Settings ��� ����� � ������ �������� ����������.</param>
        public ScanEventForm(MainForm f1, Settings f2)
        {
            InitializeComponent();
            _mainForm = f1;
            _settingsForm = f2;
        }

        /// <summary>
        /// ����� ��������� ������ "������ �����������". ��������� ������� �����, ������� ������� �����.
        /// </summary>
        private void buttonOK_Click(object sender, EventArgs e)
        {
            _mainForm.ourEventCode = this.ourEventCode;
            _mainForm.ourEventName = this.ourEventName;
            this.Close();
            _mainForm.Show();
        }

        /// <summary>
        /// ����� ��������� ������ "������". ��������� ������� �����, ������� ����� ��������.
        /// </summary>
        private void buttonCancel_Click(object sender, EventArgs e)
        {
            this.Close();
            _settingsForm.Show();
        }
    }
}