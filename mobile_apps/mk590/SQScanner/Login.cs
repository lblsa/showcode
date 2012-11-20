//-----------------------------------------------------------------------------------
// FILENAME: Login.cs
// Copyright � 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: ���� ����� ����� ���-���� ��� ������� � ���������� ����������. 
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
    /// ����� ����� ����� ���-���� ��� ������� � ���������� ����������.
    /// </summary>
    public partial class Login : Form
    {
        private const string pathToPinFile = "\\Application\\ShowCode\\pin.txt";

        private MainForm _mainForm;

        /// <summary>
        /// ����������� ����������� �����.
        /// </summary>
        public Login()
        {
            InitializeComponent();
        }

        /// <summary>
        /// ������������� ����������� �����.
        /// </summary>
        /// <param name="f1">���������� ��� ����� � ������� ������ ������������. ����� ��� �������� ������� �����.</param>
        public Login(MainForm f1)
        {
            InitializeComponent();
            _mainForm = f1;
        }

        // ����� ������ ������� ��������� ������� �� �������� ������� ���-����.
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
        /// ����� ������� ���������� ���-����.
        /// </summary>
        private void clearButton_Click(object sender, EventArgs e)
        {
            passwordTextBox.Text = "";
        }

        /// <summary>
        /// ����� ������������ ������� ������ "������" � ���������� � ������� ���� ������������ �������.
        /// </summary>
        private void cancelButton_Click(object sender, EventArgs e)
        {
            _mainForm.Show();
            this.Close();
        }

        /// <summary>
        /// ����� ������������ ������� ������ "����".
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
                MessageBox.Show("������ �������� ���-���!", "��������!");
            }
        }

        /// <summary>
        /// ����� ��������� �� ����� ���-��� ��� ������������� �������� ���������.
        /// </summary>
        /// <returns>����� ���-����.</returns>
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
                MessageBox.Show("�� ������� ��������� ���� � ���-�����!\n" + e.Message, "��������!");
            }
            
            return text;
        }
    }
}