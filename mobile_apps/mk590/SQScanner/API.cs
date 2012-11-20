//--------------------------------------------------------------------
// FILENAME: API.cs
// Copyright � 2011 Motorola Solutions, Inc. All rights reserved.
// -------------------------------------------------------------------
// DESCRIPTION: ��������� ���, ����������������� � API �������� 
// Symbol.Barcode ���������� EMDK ��� .NET.
//--------------------------------------------------------------------
using System;
using System.Windows.Forms;

namespace SQScanner
{
	/// <summary>
    /// �����, ����������������� � API �������� Symbol.Barcode ���������� EMDK ��� .NET 
	/// </summary>
	class API
	{
		private Symbol.Barcode.Reader myReader = null;
		private Symbol.Barcode.ReaderData myReaderData = null;
		private System.EventHandler myReadNotifyHandler = null;
		private System.EventHandler myStatusNotifyHandler =  null;

		/// <summary>
		/// ����� ������������� Reader'a.
		/// </summary>
		public bool InitReader()
		{
			// ���� Reader ��� ���������������, ���������� �������������.
			if (myReader != null)
			{
				return false;
			}
			else // ����� �������������� Reader.
			{
				try
				{
					// �������� ���������� ������������.
					Symbol.Generic.Device MyDevice =
						Symbol.StandardForms.SelectDevice.Select(
						Symbol.Barcode.Device.Title,
						Symbol.Barcode.Device.AvailableDevices);

					if (MyDevice == null)
					{
						MessageBox.Show("���������� �� ������!", "�������� ����������!");
						return false;
					}

					// ������� ������ Reader'a ��� ��������� ����������.
					myReader = new Symbol.Barcode.Reader(MyDevice);

					// ������� ������ ������ Reader'a.
					myReaderData = new Symbol.Barcode.ReaderData(
						Symbol.Barcode.ReaderDataTypes.Text,
						Symbol.Barcode.ReaderDataLengths.MaximumLabel);

					// �������� Reader.
					myReader.Actions.Enable();

                    // In this sample, we are setting the aim type to trigger. 
                    switch (myReader.ReaderParameters.ReaderType)
                    {
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_IMAGER:
                            myReader.ReaderParameters.ReaderSpecific.ImagerSpecific.AimType = Symbol.Barcode.AIM_TYPE.AIM_TYPE_TRIGGER;
                            break;
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_LASER:
                            myReader.ReaderParameters.ReaderSpecific.LaserSpecific.AimType = Symbol.Barcode.AIM_TYPE.AIM_TYPE_TRIGGER;
                            break;
                        case Symbol.Barcode.READER_TYPE.READER_TYPE_CONTACT:
                            // AimType is not supported by the contact readers.
                            break;
                    }
                    myReader.Actions.SetParameters();
				}

                // ����� ����� �������������� ��������
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("InitReader")+"\n" +
						Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
						Resources.GetString("Result") +" = " + (Symbol.Results)((uint)ex.Result)
						);

					return false;
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("InitReader") + "\n" +
						Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);

					return false;
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("InitReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);

					return false;
				};

				return true;
			}
		}

		/// <summary>
		/// ���������� ����������� � ������� ������ Reader'a.
		/// </summary>
		public void TermReader()
		{
			// ���� ������ Reader'a ������...
			if (myReader != null)
			{
				try
				{
					// ������������� �������� ���� �����������.
					StopRead();

                    // ����������� ��� ����������� �����������, ���� ������������ ��� ����� �� ������.
					DetachReadNotify();
					DetachStatusNotify();

					// ��������� Reader.
					myReader.Actions.Disable();

					// ����������� ������ Reader'a.
					myReader.Dispose();

					// �������� ������ �� ������ Reader'a.
					myReader = null;
				}

                // ����� �������������� �������� ��� ������������ ������� Reader'a.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}

			// ����� ������������ ������� Reader'a, ����������� ������ ������ Reader'a. 
			if (myReaderData != null)
			{
				try
				{
					// ����������� ������ ������.
					myReaderData.Dispose();

					// �������� ������ �� ������ ������.
					myReaderData = null;
				}

                // ����� �������������� �������� ��� ������������ ������� ������ Reader'a.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("TermReader") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}
		}

		/// <summary>
		/// ����� ��������� ������ Reader'��.
		/// </summary>
		public void StartRead(bool toggleSoftTrigger)
		{
			// ��������� ������� �� � ��� ������� Reader'a � ������ Reader'a.
			if ((myReader != null) &&
				(myReaderData != null))

				try
				{
					if ( !myReaderData.IsPending )
					{
                        // Submit a read.
                        myReader.Actions.Read(myReaderData);

                        if (toggleSoftTrigger && myReader.Info.SoftTrigger == false)
                        {
                            myReader.Info.SoftTrigger = true;
                        }
					}
				}

                // ����� �������������� �������� ��� ���������� ������ Reader'��.
				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result));
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("StartRead") + "\n" +
                        Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
		}

		/// <summary>
		/// ����� ���������� ���������� ������� Reader'��.
		/// </summary>
		public void StopRead()
		{
			//If we have a reader
			if (myReader != null)
			{
				try
				{
					// Flush (Cancel all pending reads).
                    if (myReader.Info.SoftTrigger == true)
                    {
                        myReader.Info.SoftTrigger = false;
                    }
                    myReader.Actions.Flush();
				}

				catch (Symbol.Exceptions.OperationFailureException ex)
				{
					MessageBox.Show(Resources.GetString("StopRead") + "\n" +
                        Resources.GetString("OperationFailure") + "\n" + ex.Message +
						"\n" +
                        Resources.GetString("Result") + " = " + (Symbol.Results)((uint)ex.Result)
						);
				}
				catch (Symbol.Exceptions.InvalidRequestException ex)
				{
                    MessageBox.Show(Resources.GetString("StopRead") + "\n" +
                        Resources.GetString("InvalidRequest") + "\n" +
						ex.Message);
				}
				catch (Symbol.Exceptions.InvalidIndexerException ex)
				{
                    MessageBox.Show(Resources.GetString("StopRead") + "\n" +
						Resources.GetString("InvalidIndexer") + "\n" +
						ex.Message);
				};
			}
		}

		/// <summary>
		/// Provides the access to the Symbol.Barcode.Reader reference.
		/// The user can use this reference for his additional Reader - related operations.
		/// </summary>
		public Symbol.Barcode.Reader Reader 
		{
			get
			{
				return myReader;
			}
		}

		/// <summary>
		/// ����� ������������ ���������� ����������� � ������� ������.
		/// </summary>
		public void AttachReadNotify(System.EventHandler ReadNotifyHandler)
		{
			// If we have a reader
			if( myReader != null)
			{
				// Attach the read notification handler.
				myReader.ReadNotify += ReadNotifyHandler;
				myReadNotifyHandler = ReadNotifyHandler;
			}

		}

		/// <summary>
		/// ����� ����������� ���������� ����������� � ������� ������.
		/// </summary>
		public void DetachReadNotify()
		{
			if(( myReader != null)&&(myReadNotifyHandler!=null))
			{
				// Detach the read notification handler.
				myReader.ReadNotify -= myReadNotifyHandler;
                myReadNotifyHandler = null;
			}
		}

		/// <summary>
		/// ����� ������������ ���������� ����������� � ������� ����������.
		/// </summary>
		public void AttachStatusNotify(System.EventHandler StatusNotifyHandler)
		{
			// If we have a reader
			if( myReader != null)
			{
				// Attach status notification handler.
				myReader.StatusNotify += StatusNotifyHandler;
				myStatusNotifyHandler = StatusNotifyHandler;
			}
		}

		/// <summary>
		/// ����� ����������� ���������� ����������� � ������� ����������.
		/// </summary>
		public void DetachStatusNotify()
		{
			// If we have a reader registered for receiving the status notifications
			if((myReader != null)&&(myStatusNotifyHandler!=null))
			{
				// Detach the status notification handler.
				myReader.StatusNotify -= myStatusNotifyHandler;
                myStatusNotifyHandler = null;
			}
		}
	}
}