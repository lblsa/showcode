//-----------------------------------------------------------------------------------
// FILENAME: MainForm.cs
// Copyright © 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: Файл основной логики работы программы сканирования QR-кодов на основе 
// библиотеки EMDK для .NET от Motorola. 
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
    public partial class MainForm : Form
    {
        /// Статусные сообщения для информирования пользователя
        const string STATUS_MINUS_1 = "Указанный билет не существует";                             // statusCode = -1
        const string STATUS_MINUS_2 = "Возникла проблема соединения с сервером";                   // statusCode = -2
        const string STATUS_MINUS_3 = "Указанное мероприятие не существует";                       // statusCode = -3
        const string STATUS_0 = "Перед сканированием билетов задайте мероприятие";                 // statusCode = 0
        const string STATUS_1 = "Мероприятие задано, теперь можно сканировать билеты";             // statusCode = 1
        const string STATUS_2 = "Билет забронирован, но не оплачен";                               // statusCode = 2
        const string STATUS_3 = "Билет оплачен и действителен";                                    // statusCode = 3
        const string STATUS_4 = "Билет не оплачен, бронь просрочена";                              // statusCode = 4
        const string STATUS_5 = "Билет уже использован";                                           // statusCode = 5
        const string STATUS_6 = "Билет сегодня не действует";                                      // statusCode = 6

        const string HTTP_REQUEST_ADDRESS_PART1 = "http://showcode.ru/";
        const string HTTP_REQUEST_ADDRESS_PART2 = "events/webApi?ticket=";
        const string HTTP_REQUEST_ADDRESS_PART4 = "&event=";
        const string HTTP_REQUEST_ADDRESS_TICKET_VIEW = "http://showcode.ru/ticket/view/";
        //const string HTTP_REQUEST_GET_EVENT_PART1 = "events/webApi?event=";
        //const string HTTP_REQUEST_GET_EVENT_PART2 = "&name=1";
        //const string HTTP_REQUEST_GET_EVENT_PART3 = "&count=1";
        const string HTTP_REQUEST_MARK_TICKET = "&markticket=1";
        //const string HTTP_REQUEST_DONT_MARK_TICKET = "&markticket=0";

        //const int QR_EVENT_LENGTH = 39;
        //const int QR_EVENT_API_LENGTH = 7;
        //const int QR_TICKET_LENGTH = 43;
        const int QR_TICKET_API_LENGTH = 31;
        const int QR_TICKET_CODE_LENGTH = 24; //было 8

        //private WebBrowser myWebBrowser;
        private API ticketReader = null;

        private EventHandler myReadNotifyHandler = null;
        private EventHandler myStatusNotifyHandler = null;
        private EventHandler myFormActivatedEventHandler = null;
        private EventHandler myFormDeactivatedEventHandler = null;

        public string ourEventCode = "";    // 32-символьная строка с кодом мероприятия.ы
        public string ourEventName = "";    // Название мероприятия.

        /// <summary>
        /// Флаг позволяет отслеживать инициализирован ли объект ридера или нет.
        /// </summary>
        private bool isReaderInitiated = false;

        const UInt32 SWP_HIDEWINDOW = 0x0080;

        // Импортируемые DLL из Windows API
        [DllImport("coredll.dll", CharSet=CharSet.Auto)]
        private static extern IntPtr FindWindow(string lpClassName, string lpWindowName);      
        [DllImport("coredll.dll")]
        private static extern bool SetWindowPos(IntPtr hWnd, int hWndInsertAfter, int X, int Y, int cx, int cy, uint uFlags);
      
        /// <summary>
        /// Метод скрывает панель задач
        /// </summary>
        private void HideTaskBar()
        {
            IntPtr hWndTaskBar = FindWindow("HHTaskBar", "");
            SetWindowPos(hWndTaskBar, 0, 0, 0, 0, 0, SWP_HIDEWINDOW);
        }

        /// <summary>
        /// Стандартный метод конструктора главной формы приложения.
        /// </summary>
        public MainForm()
        {
            InitializeComponent();
        }

        /// <summary>
        /// Метод вызывается при открытии формы приложения.
        /// </summary>
        private void Form1_Load(object sender, EventArgs e)
        {
            HideTaskBar();
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;

            // Создаем и инициализируем объект сканера штрих-кодов.
            this.ticketReader = new API();
            this.isReaderInitiated = this.ticketReader.InitReader();

            if (!(this.isReaderInitiated))// Если объект Reader'a не был инициализирован...
            {
                // Показываем сообщение об ошибке и закрываем приложение.
                MessageBox.Show("Произошла ошибка инициализации Reader'a!");
                Application.Exit();
            }
            else // Если объект Reader'a был инициализирован...
            {
                // создаем обработчик уведомлений о статусе Reader'a.
                this.myStatusNotifyHandler = new EventHandler(myReader_StatusNotify);
                ticketReader.AttachStatusNotify(myStatusNotifyHandler);
            }

            // Переходим в режим ожидания билетов для сканирования и создаем обработчик уведомлений об отсканированных билетах.
            //this.ticketReader.StartRead(true);
            this.labelInfo.Text = "Задайте мероприятие";
            this.myReadNotifyHandler = new EventHandler(myReader_ReadNotify);
            ticketReader.AttachReadNotify(myReadNotifyHandler);

            // Для обработки ситуаций, когда происходит активация и деактивация формы приложения создаем два обработчика,
            // которые будут вызываться в случае активации и деактивации формы приложения.
            // Метод MainForm_Activated будет вызываться при передаче фокуса форме, устройство переходит в режим ожидания билетов для сканирования.
            // Метод MainForm_Deactivate будет вызываться при потере формой фокуса, устройство выходит из режима ожидания билетов для сканирования. 
            myFormActivatedEventHandler = new EventHandler(MainForm_Activated);
            myFormDeactivatedEventHandler = new EventHandler(MainForm_Deactivate);
            this.Activated += myFormActivatedEventHandler;
            this.Deactivate += myFormDeactivatedEventHandler;
            return;
        }

        /// <summary>
        /// Метод вызывается при закрытии формы приложения.
        /// </summary>
        private void Form1_Closing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            if (isReaderInitiated)
            {
                // Удаляем ридер
                ticketReader.DetachReadNotify();
                ticketReader.DetachStatusNotify();
                ticketReader.TermReader();

                // Удаляем обработчики событий формы, созданные при создании формы приложения.
                this.Activated -= myFormActivatedEventHandler;
                this.Deactivate -= myFormDeactivatedEventHandler;
            }
            return;
        }

        /// <summary>
        /// Обработчик уведомлений о статусе считывателя штрих-кодов.
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
                // Получаем статус Reader'a
                Symbol.Barcode.BarcodeStatus TheStatusData = this.ticketReader.Reader.GetNextStatus();

                switch (TheStatusData.State)
                {
                    case Symbol.Barcode.States.WAITING:
                        this.ticketReader.StopRead();
                        this.ticketReader.StartRead(true);
                        break;

                    case Symbol.Barcode.States.IDLE:
                        //this.labelInfo.Text = "Поднесите билет к сканеру";
                        break;

                    case Symbol.Barcode.States.READY:
                        //this.labelInfo.Text = "Поднесите билет к сканеру";
                        break;

                    default:
                        this.labelInfo.Text = TheStatusData.Text;
                        break;
                }
            }
        }

        /// <summary>
        /// Метод обработки уведомлений о состоянии Reader'a.
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
                        // Обрабатываем прочитанные данные билета и готовимся читать следующий билет.
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
                        string sMsg = "Не удалось прочитать код билета!\n"
                            + "Текст QR-кода = "
                            + (TheReaderData.Result).ToString();
                        MessageBox.Show(sMsg,"Возникла ошибка!");

                        if (TheReaderData.Result == Symbol.Results.E_SCN_READINCOMPATIBLE)
                        {
                            // If the failure is E_SCN_READINCOMPATIBLE, exit the application.
                            MessageBox.Show("Возникла критическая ошибка! Приложение будет завершено.", "Критическая ошибка");
                            this.Close();
                            return;
                        }

                        break;
                }
            }
        }

        /// <summary>
        /// Метод задает статус состояния программы и показывает пропускать или нет билет.
        /// </summary>
        /// <param name="statusCode">Переменная статуса прочитанного кода билета.</param>
        private void setStatusCodeWithAlert(int statusCode)
        {
            string title;
            if (statusCode == 3)
                title = "Билет пропущен!";
            else
                title = "Билет НЕ пропущен!";
            string message;
            switch (statusCode) 
            {
                case -3: message = STATUS_MINUS_3;  this.labelMain.ForeColor = Color.Red;   break;
                case -2: message = STATUS_MINUS_2;  this.labelMain.ForeColor = Color.Red;   break;
                case -1: message = STATUS_MINUS_1;  this.labelMain.ForeColor = Color.Red;   break;
                case 0:  message = STATUS_0;        this.labelMain.ForeColor = Color.Red;   break;
                case 1:  message = STATUS_1;        this.labelMain.ForeColor = Color.Red;   break;
                case 2:  message = STATUS_2;        this.labelMain.ForeColor = Color.Red;   break;
                // Пропускаем: если statusCode = 3
                case 3:  message = STATUS_3;        this.labelMain.ForeColor = Color.LightGreen; break;
                case 4:  message = STATUS_4;        this.labelMain.ForeColor = Color.Red;   break;
                case 5:  message = STATUS_5;        this.labelMain.ForeColor = Color.Red;   break;
                case 6:  message = STATUS_6;        this.labelMain.ForeColor = Color.Red;   break;
                default: message = STATUS_MINUS_1;  this.labelMain.ForeColor = Color.Red;   break;
            }
            this.labelInfo.Text = message;
            this.labelMain.Text = title;
            
        }

        /// <summary>
        /// Метод обработки текста, полученного в результате сканирования.
        /// </summary>
        private void HandleData(Symbol.Barcode.ReaderData myReaderDataObject)
        {
            // Проверяем, задано ли мероприятие.
            if (this.ourEventCode == "")
            {
                this.labelInfo.Text = "Задайте мероприятие!";
                return;
            }

            // Проверяем соответствие билета формату билета.
            bool ticketIsProper = false;
            if (myReaderDataObject.Text.Length >= QR_TICKET_API_LENGTH)
                if (myReaderDataObject.Text.Substring(0, QR_TICKET_API_LENGTH) == HTTP_REQUEST_ADDRESS_TICKET_VIEW)
                    ticketIsProper = true;

            if (ticketIsProper) {
                // Идем на сервер за данными о валидности билета
                // Извлекаем из распознанного QR-кода идентификатор билета
                string httpRequestAddressPart3 = myReaderDataObject.Text.Substring (QR_TICKET_API_LENGTH, QR_TICKET_CODE_LENGTH);

                // Составляем строку запроса, с учетом кода билета и кода мероприятия
                string requestString = HTTP_REQUEST_ADDRESS_PART1 + HTTP_REQUEST_ADDRESS_PART2 + httpRequestAddressPart3 + HTTP_REQUEST_ADDRESS_PART4 + this.ourEventCode + HTTP_REQUEST_MARK_TICKET;
             
                string serverResponseString;
                // Обращаемся к серверу и берем у него ответ о валидности билета
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
                catch(Exception e)
                {
                    serverResponseString = "";
                    return;
                }
        
                int returnCode;
                if (serverResponseString == null)
                    returnCode = - 2;
                else
                    try 
                    {
                        // Смотрим код возврата сервера.
                        returnCode = System.Convert.ToInt16(serverResponseString.Substring(0,1));
                    }
                    catch (Exception e)
                    {
                        returnCode = -1;
                    }

                switch (returnCode) 
                {
                    case -2:setStatusCodeWithAlert(-2); break;
                    case -1:setStatusCodeWithAlert(-1); break;
                    case 0: setStatusCodeWithAlert(2);  break;
                    case 1: setStatusCodeWithAlert(3);  break;
                    case 2: setStatusCodeWithAlert(4);  break;
                    case 3: setStatusCodeWithAlert(5);  break;
                    case 4: setStatusCodeWithAlert(6);  break;
                    default:    break;
                }
            } else 
            {
                // Формат QR-кода не соответствует формату билета
                this.labelInfo.Text = "Неверный формат билета!";
            }
        }

        /// <summary>
        /// Метод обработки нажатия на кнопку "Настройки".
        /// </summary>
        private void buttonSettings_Click(object sender, EventArgs e)
        {
            Login loginForm = new Login(this);
            this.Hide();
            loginForm.Show();
        }

        /// <summary>
        /// Метод начала ожидания объекта для сканирования при активации формы приложения (непрерывное сканирование).
        /// </summary>
        private void MainForm_Activated(object sender, EventArgs e)
        {
            if (this.ourEventName != "")
            {
                this.labelInfo.Text = "";
                this.ticketReader.StartRead(true);
                this.ticketReader.AttachStatusNotify(myStatusNotifyHandler);
                this.ticketReader.AttachReadNotify(myReadNotifyHandler);
                this.labelEvent.Text = this.ourEventName;
            }
        }

        /// <summary>
        /// Метод завершения ожидания объекта для сканирования при деактивации формы приложения.
        /// </summary>
        private void MainForm_Deactivate(object sender, EventArgs e)
        {
            this.ticketReader.StopRead();
            this.ticketReader.DetachStatusNotify();
            this.ticketReader.DetachReadNotify(); 
        }
    }
}