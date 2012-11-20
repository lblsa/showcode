//-----------------------------------------------------------------------------------
// FILENAME: ScanEventForm.cs
// Copyright © 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: Форма отвечает за сканирование QR-кода мероприятия. 
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
    /// Класс формы сканирования мероприятия.
    /// </summary>
    public partial class ScanEventForm : Form
    {
        /// Статусные сообщения для информирования пользователя
        const string STATUS_MINUS_1 = "Указанный билет не существует";                             // statusCode = -1
        const string STATUS_MINUS_2 = "Возникла проблема соединения с сервером";                   // statusCode = -2
        const string STATUS_MINUS_3 = "Указанное мероприятие не существует";                       // statusCode = -3
        //const string STATUS_0 = "Перед сканированием билетов задайте мероприятие";                 // statusCode = 0
        const string STATUS_1 = "QR-код мероприятия считан";                                       // statusCode = 1
        //const string STATUS_2 = "Билет забронирован, но не оплачен";                               // statusCode = 2
        //const string STATUS_3 = "Билет оплачен и действителен";                                    // statusCode = 3
        //const string STATUS_4 = "Билет не оплачен, бронь просрочена";                              // statusCode = 4
        //const string STATUS_5 = "Билет уже использован";                                           // statusCode = 5
        //const string STATUS_6 = "Билет сегодня не действует";                                      // statusCode = 6

        const string STATUS_WRONG_FORMAT = "Неверный формат QR-кода мероприятия!";
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

        // Создаем объекты форм сканирования билетов и настроек для того, чтобы можно 
        // было вернуться на эти формы из данной формы при ее закрытии.
        private MainForm _mainForm;
        private Settings _settingsForm;

        private API ticketReader = null;

        private EventHandler myReadNotifyHandler = null;
        private EventHandler myStatusNotifyHandler = null;

        public string ourEventCode = "";    // 32-символьная строка с кодом мероприятия
        public string ourEventName = "";    // Название мероприятия

        /// <summary>
        /// Флаг позволяет отслеживать инициализирован ли объект ридера или нет.
        /// </summary>
        private bool isReaderInitiated = false;

        /// <summary>
        /// Метод вызывается при открытии формы и инициализирует Reader.
        /// </summary>
        private void ScanEventForm_Load(object sender, EventArgs e)
        {
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

            // Переходим в режим ожидания QR-кода мероприятия для сканирования и создаем обработчик уведомлений об отсканированных QR-кодах.
            this.ticketReader.StartRead(true);
            this.myReadNotifyHandler = new EventHandler(myReader_ReadNotify);
            this.ticketReader.AttachReadNotify(myReadNotifyHandler);
            return;
        }

        /// <summary>
        /// Метод вызывается при закрытии формы приложения.
        /// </summary>
        private void ScanEventForm_Closing(object sender, CancelEventArgs e)
        {
            if (this.isReaderInitiated)
            {
                // Удаляем ридер
                this.ticketReader.DetachReadNotify();
                this.ticketReader.DetachStatusNotify();
                this.ticketReader.TermReader();
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
                        string sMsg = "Не удалось прочитать код мероприятия!\n"
                            + "Текст QR-кода = "
                            + (TheReaderData.Result).ToString();
                        MessageBox.Show(sMsg, "Возникла ошибка!");

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
        /// Метод задает статус состояния программы и показывает результат сканирования мероприятия.
        /// </summary>
        /// <param name="statusCode">Переменная кода статуса прочитанного QR-кода</param>
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
        /// Метод обработки текста, полученного в результате сканирования.
        /// </summary>
        private void HandleData(Symbol.Barcode.ReaderData myReaderDataObject)
        {
            // Проверяем код мероприятия на соответствие принятому формату
            bool eventIsProper = false;
            if (myReaderDataObject.Text.Length == QR_EVENT_LENGTH)
                if (myReaderDataObject.Text.Substring(0, QR_EVENT_API_LENGTH) == QR_EVENT_API_NAME)
                    eventIsProper = true;
            
            if (eventIsProper)
            {
                // Идем на сервер за данными о валидности мероприятия
                // Извлекаем из распознанного QR-кода идентификатор мероприятия
                this.ourEventCode = myReaderDataObject.Text.Substring(QR_EVENT_API_LENGTH, QR_EVENT_LENGTH - QR_EVENT_API_LENGTH);

                // Составляем строку запроса, с кода мероприятия
                string requestString = HTTP_REQUEST_ADDRESS_PART1 + HTTP_REQUEST_GET_EVENT_PART1 + this.ourEventCode + HTTP_REQUEST_GET_EVENT_PART2;

                // Обращаемся к серверу и берем у него ответ о названии мероприятия
                string serverResponseString;
                // Забираем ответ сервера
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
                // Формат QR-кода не соответствует формату мероприятия
                this.labelInfo.Text = STATUS_WRONG_FORMAT;
            }
        }

        /// <summary>
        /// Стандартный конструктор класса ScanEventForm.
        /// </summary>
        public ScanEventForm()
        {
            InitializeComponent();
        }

        /// <summary>
        /// Перегруженный конструктор класса ScanEventForm.
        /// </summary>
        /// <param name="f1">Переменная типа MainForm для связи с главной формой приложения.</param>
        /// <param name="f2">Переменная типа Settings для связи с формой настроек приложения.</param>
        public ScanEventForm(MainForm f1, Settings f2)
        {
            InitializeComponent();
            _mainForm = f1;
            _settingsForm = f2;
        }

        /// <summary>
        /// Метод обработки кнопки "Задать мероприятие". Закрывает текущую форму, выводит главную форму.
        /// </summary>
        private void buttonOK_Click(object sender, EventArgs e)
        {
            _mainForm.ourEventCode = this.ourEventCode;
            _mainForm.ourEventName = this.ourEventName;
            this.Close();
            _mainForm.Show();
        }

        /// <summary>
        /// Метод обработки кнопки "Отмена". Закрывает текущую форму, выводит форму настроек.
        /// </summary>
        private void buttonCancel_Click(object sender, EventArgs e)
        {
            this.Close();
            _settingsForm.Show();
        }
    }
}