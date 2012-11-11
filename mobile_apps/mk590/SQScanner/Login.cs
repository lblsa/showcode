//-----------------------------------------------------------------------------------
// FILENAME: Login.cs
// Copyright © 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: Файл формы ввода пин-кода для доступа к настройкам приложения. 
// ----------------------------------------------------------------------------------
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace SQScanner
{
    /// <summary>
    /// Класс формы ввода пин-кода для доступа к настройкам приложения.
    /// </summary>
    public partial class Login : Form
    {
        private const string pathToPinFile = "\\Application\\ShowCode\\pin.txt";

        private MainForm _mainForm;

        /// <summary>
        /// Стандартный конструктор формы.
        /// </summary>
        public Login()
        {
            InitializeComponent();
        }

        /// <summary>
        /// Перегруженный конструктор формы.
        /// </summary>
        /// <param name="f1">Переменная для связи с главной формой сканирования. Нужна при закрытии текущей формы.</param>
        public Login(MainForm f1)
        {
            InitializeComponent();
            _mainForm = f1;
        }

        // Далее группа методов обработки нажатия на цифровые клавиши пин-кода.
        private void button1_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "1";
        }

        private void button2_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "2";
        }

        private void button3_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "3";
        }

        private void button4_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "4";
        }

        private void button5_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "5";
        }

        private void button6_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "6";
        }

        private void button7_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "7";
        }

        private void button8_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "8";
        }

        private void button9_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "9";
        }

        private void button_0_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = passwordTextBox.Text + "0";
        }

        /// <summary>
        /// Метод очистки введенного пин-кода.
        /// </summary>
        private void clearButton_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = "";
        }

        /// <summary>
        /// Метод обрабатывает нажатие кнопки "Отмена" и возвращает в главное окно сканирования билетов.
        /// </summary>
        private void cancelButton_Click(object sender, EventArgs e)
        {
            _mainForm.Show();
            this.Close();
        }

        /// <summary>
        /// Метод обрабатывает нажатие кнопки "Ввод".
        /// </summary>
        private void enterButton_Click(object sender, EventArgs e)
        {
            if (passwordTextBox.Text == readPinFromFile())
            {
                Settings settingsForm = new Settings(_mainForm);
                this.Hide();
                settingsForm.Show();
            }
            else
            {
                passwordTextBox.Text = "";
                MessageBox.Show("Введен неверный пин-код!", "Внимание!");
            }
        }

        /// <summary>
        /// Метод считывает из файла пин-код для разблокировки настроек программы.
        /// </summary>
        /// <returns>Текст пин-кода.</returns>
        private string readPinFromFile()
        {
            string text = "";
            try
            {
                // Create an instance of StreamReader to read from a file.
                // The using statement also closes the StreamReader.
                using (StreamReader sr = new StreamReader(pathToPinFile))
                {
                    string line;
                    while ((line = sr.ReadLine()) != null)
                        text = text + line;
                }
            }
            catch (Exception e)
            {
                MessageBox.Show("Не удалось прочитать файл с пин-кодом!\n" + e.Message, "Внимание!");
            }
            
            return text;
        }
    }
}