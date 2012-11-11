//-----------------------------------------------------------------------------------
// FILENAME: Settings.cs
// Copyright © 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: Форма настроек приложения. 
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
    /// Класс формы настроек приложения.
    /// </summary>
    public partial class Settings : Form
    {
        /// <summary>
        /// Переменная для хранения ссылки на главную форму приложения, отвечающую за сканирование.
        /// </summary>
        private MainForm _mainForm;

        const UInt32 SWP_SHOWWINDOW = 0x0040;

        [DllImport("coredll.dll", CharSet = CharSet.Auto)]
        private static extern IntPtr FindWindow(string lpClassName, string lpWindowName);

        [DllImport("coredll.dll")]
        private static extern bool SetWindowPos(IntPtr hWnd, int hWndInsertAfter, int X, int Y, int cx, int cy, uint uFlags);

        /// <summary>
        /// Метод отображает скрытую панель инструментов.
        /// </summary>
        private void showTaskBar()
        {
            IntPtr hWndTaskBar = FindWindow("HHTaskBar", "");
            SetWindowPos(hWndTaskBar, 0, 0, 213, 320, 27, SWP_SHOWWINDOW);
        }

        /// <summary>
        /// Стандартный конструктор формы Settings
        /// </summary>
        public Settings()
        {
            InitializeComponent();
        }

        /// <summary>
        /// Перегруженный конструктор формы Settings
        /// </summary>
        /// <param name="f1">Параметр передает ссылку на главную форму.</param>
        public Settings(MainForm f1)
        {
            InitializeComponent();
            _mainForm = f1;
        }

        /// <summary>
        /// Метод инициирует сканирование QR-кода мероприятия для задания мероприятия.
        /// </summary>
        private void setEventButton_Click(object sender, EventArgs e)
        {
            ScanEventForm myScanEventForm = new ScanEventForm(_mainForm, this);
            this.Hide();
            myScanEventForm.Show();
        }

        /// <summary>
        /// Метод завершает работу приложения.
        /// </summary>
        private void exitButton_Click(object sender, EventArgs e)
        {
            showTaskBar();
            Application.Exit();
        }

        /// <summary>
        /// Метод возвращает управление экрану сканирования билетов
        /// </summary>
        private void cancelButton_Click(object sender, EventArgs e)
        {
            _mainForm.Show();
            this.Close();
        }

        /// <summary>
        /// Метод вызывает форму изменения пин-кода.
        /// </summary>
        private void buttonPinChange_Click(object sender, EventArgs e)
        {
            PinChange myPinChange = new PinChange(this);
            this.Hide();
            myPinChange.Show();
        }
    }
}